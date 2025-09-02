<?php

use App\Models\Role;
use App\Models\User;
use App\Services\CreditService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Client\HttpClientException;

it('consumes credits for non-admin', function () {
    $role = Role::firstOrCreate(['slug' => 'member'], ['name' => 'Member', 'daily_credits' => 2]);
    $user = User::factory()->create([
        'password' => Hash::make('password'),
        'credits_remaining' => 2,
        'last_credits_reset_at' => now(),
    ]);
    $user->roles()->sync([$role->id]);

    $svc = app(CreditService::class);
    $svc->consume($user->fresh());
    expect($user->fresh()->credits_remaining)->toBe(1);

    $svc->consume($user->fresh());
    expect($user->fresh()->credits_remaining)->toBe(0);

    expect(fn () => $svc->consume($user->fresh()))
        ->toThrow(HttpClientException::class, 'Too Many Requests');
});

it('admin unlimited', function () {
    $adminRole = Role::firstOrCreate(['slug' => 'admin'], ['name' => 'Administrator', 'daily_credits' => 0]);
    $user = User::factory()->create([
        'credits_remaining' => null,
        'last_credits_reset_at' => now(),
    ]);
    $user->roles()->sync([$adminRole->id]);

    $svc = app(CreditService::class);
    $svc->consume($user);
    $svc->consume($user);
    expect($user->fresh()->credits_remaining)->toBeNull();
});