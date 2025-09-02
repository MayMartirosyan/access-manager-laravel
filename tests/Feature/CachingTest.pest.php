<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

it('caches users index', function () {
    Cache::flush();
  
    $admin = User::factory()->create(['password' => Hash::make('password')]);
    $role = Role::firstOrCreate(['slug' => 'member'], ['name' => 'Member', 'daily_credits' => 10]);
    $admin->roles()->syncWithoutDetaching([$role->id]);

    $token = $admin->createToken('t')->plainTextToken;

    $response = $this->withToken($token)->getJson('/api/v1/users')->assertOk();
    expect($response->json('data'))->toBeArray()->toHaveCount(1);
    expect(Cache::has('users.index.' . md5(json_encode([]))))->toBeTrue();
});