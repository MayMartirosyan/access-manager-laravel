<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;

class AuthService extends BaseService
{
    public function register(string $name, string $email, string $password): User
    {
        return $this->tx(function () use ($name, $email, $password) {
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'credits_remaining' => 100,
                'last_credits_reset_at' => now(),
            ]);

            $memberRole = Role::where('slug', 'member')->firstOrFail();
            $user->roles()->sync([$memberRole->id]);
            Cache::flush();
            return $user;
        });
    }

    public function createToken(User $user, ?string $device = null): string
    {
        return $user->createToken($device ?: 'api')->plainTextToken;
    }

    public function revokeTokens(User $user): void
    {
        $user->tokens()->delete();
    }
}