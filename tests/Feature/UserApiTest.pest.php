<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

beforeEach(function () {
    $this->admin = User::factory()->create(['password' => Hash::make('password')]);
    $adminRole = Role::firstOrCreate(['slug' => 'admin'], ['name' => 'Administrator', 'daily_credits' => 100]);
    $this->admin->roles()->syncWithoutDetaching([$adminRole->id]);
    $this->token = $this->admin->createToken('tests')->plainTextToken;

    $this->memberRole = Role::firstOrCreate(['slug' => 'member'], ['name' => 'Member', 'daily_credits' => 3]);
});

it('lists users with credits middleware', function () {
    $res = $this->withToken($this->token)->getJson('/api/v1/users')->assertOk();
    expect($res->json('data'))->toBeArray();
});


