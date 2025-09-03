<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

beforeEach(function () {
    $this->admin = User::factory()->create(['password' => Hash::make('password')]);
    $adminRole = Role::firstOrCreate(['slug' => 'admin'], ['name' => 'Administrator', 'daily_credits' => 100]);
    $this->admin->roles()->sync([$adminRole->id]);
    $this->token = $this->admin->createToken('tests')->plainTextToken;

    $this->memberRole = Role::firstOrCreate(['slug' => 'member'], ['name' => 'Member', 'daily_credits' => 3]);
    $this->member = User::factory()->create();
    $this->member->roles()->sync([$this->memberRole->id]);
    $this->memberToken = $this->member->createToken('tests')->plainTextToken;
});

it('lists users with credits middleware', function () {
    $response = $this->withToken($this->token)->getJson('/api/v1/users');
    
    $response->assertOk();
    expect($response->json('data'))->toBeArray();
});

it('fails to store a user with invalid data', function () {
    $data = [
        'name' => '',
        'email' => 'invalid-email',
        'password' => 'short',
        'password_confirmation' => 'different',
        'role_ids' => [$this->memberRole->id],
    ];

    $response = $this->withToken($this->token)->postJson('/api/v1/users', $data);

    $response->assertStatus(422);
});

it('prevents non-admin from storing a user', function () {
    $data = [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'role_ids' => [$this->memberRole->id],
    ];

    $response = $this->withToken($this->memberToken)->postJson('/api/v1/users', $data);

    $response->assertStatus(403);
});


it('fails to update a user with invalid data', function () {
    $data = [
        'name' => '',
        'email' => 'invalid-email',
    ];

    $response = $this->withToken($this->token)->putJson("/api/v1/users/{$this->member->id}", $data);

    $response->assertStatus(422);
});

it('prevents non-admin from updating a user', function () {
    $data = [
        'name' => 'Updated User',
        'email' => 'updated@example.com',
        'role_ids' => [$this->memberRole->id],
    ];

    $response = $this->withToken($this->memberToken)->putJson("/api/v1/users/{$this->member->id}", $data);

    $response->assertStatus(403);
});

it('deletes a user as admin', function () {
    $response = $this->withToken($this->token)->deleteJson("/api/v1/users/{$this->member->id}");

    $response->assertStatus(204);
    $this->assertDatabaseMissing('users', ['id' => $this->member->id]);
});

it('prevents non-admin from deleting a user', function () {
    $response = $this->withToken($this->memberToken)->deleteJson("/api/v1/users/{$this->member->id}");

    $response->assertStatus(403);
});