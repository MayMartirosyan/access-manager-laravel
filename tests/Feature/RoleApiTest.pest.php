<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

function adminToken()
{
    $admin = User::where('email', 'admin@example.com')->first();
    if (!$admin) {
        $admin = User::factory()->create(['email' => 'admin@example.com', 'password' => Hash::make('password')]);
    }
    // пометим админом
    $role = Role::firstOrCreate(['slug' => 'admin'], ['name' => 'Administrator', 'daily_credits' => 0]);
    $admin->roles()->syncWithoutDetaching([$role->id]);
    return $admin->createToken('tests')->plainTextToken;
}

it('admin can CRUD roles', function () {
    $token = adminToken();

    $created = $this->withToken($token)->postJson('/api/v1/roles', [
        'name' => 'QA',
        'slug' => 'qa',
        'daily_credits' => 50,
        'meta' => ['note' => 'quality'],
    ])->assertStatus(201)->json('data');

    $id = $created['id'];

    $this->withToken($token)->getJson("/api/v1/roles/{$id}")->assertOk();

    $this->withToken($token)->putJson("/api/v1/roles/{$id}", [
        'daily_credits' => 80
    ])->assertOk();

    $this->withToken($token)->deleteJson("/api/v1/roles/{$id}")->assertStatus(204);
});

it('non-admin forbidden', function () {
    $u = User::factory()->create();
    $t = $u->createToken('tests')->plainTextToken;

    $this->withToken($t)->getJson('/api/v1/roles')->assertStatus(403);
});
