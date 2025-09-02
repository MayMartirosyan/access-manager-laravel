<?php

use App\Models\User;

it('blocks non-admin on admin routes', function () {
    $user = User::factory()->create();
    $token = $user->createToken('t')->plainTextToken;

    $this->withToken($token)->getJson('/api/v1/roles')->assertStatus(403);
});
