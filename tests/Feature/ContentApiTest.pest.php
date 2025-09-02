<?php

use App\Models\Content;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

beforeEach(function () {
    Cache::flush();

    $this->admin = User::factory()->create(['password' => Hash::make('password')]);
    $adminRole = Role::firstOrCreate(['slug' => 'admin'], ['name' => 'Administrator', 'daily_credits' => 0]);
    $this->admin->roles()->syncWithoutDetaching([$adminRole->id]);
    $this->adminToken = $this->admin->createToken('tests')->plainTextToken;

    $this->user = User::factory()->create([
        'password' => Hash::make('password'),
        'credits_remaining' => 3,
        'last_credits_reset_at' => now(),
    ]);
    $memberRole = Role::firstOrCreate(['slug' => 'member'], ['name' => 'Member', 'daily_credits' => 3]);
    $this->user->roles()->syncWithoutDetaching([$memberRole->id]);
    $this->userToken = $this->user->createToken('tests')->plainTextToken;

    Content::factory()->count(5)->create();
});

it('lists content for admin without consuming credits', function () {
    $response = $this->withToken($this->adminToken)->getJson('/api/v1/content')->assertOk();
    expect($response->json('data'))->toBeArray()->toHaveCount(5);
    expect($this->admin->fresh()->credits_remaining)->toBe(100);
});

it('lists content for member and consumes credits', function () {
    $response = $this->withToken($this->userToken)->getJson('/api/v1/content')->assertOk();
    expect($response->json('data'))->toBeArray()->toHaveCount(5);
    expect($this->user->fresh()->credits_remaining)->toBe(2); // 3 - 1 = 2
});

it('blocks content access when credits are depleted', function () {
    $this->user->update(['credits_remaining' => 0]);
    $this->withToken($this->userToken)->getJson('/api/v1/content')->assertStatus(429);
});

it('caches content index', function () {
    Cache::flush();
    $response = $this->withToken($this->adminToken)->getJson('/api/v1/content')->assertOk();
    expect($response->json('data'))->toBeArray()->toHaveCount(5);
    expect(Cache::has('content.index'))->toBeTrue();
});