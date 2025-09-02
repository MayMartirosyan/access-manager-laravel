<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Inertia\Testing\AssertableInertia;

it('displays dashboard for authenticated user', function () {
    Cache::flush();

    $user = User::factory()->create(['password' => Hash::make('password')]);
    $role = Role::firstOrCreate(['slug' => 'member'], ['name' => 'Member', 'daily_credits' => 3]);
    $user->roles()->sync([$role->id]);

    $response = $this->actingAs($user)->get('/dashboard')->assertOk();
    $response->assertInertia(
        fn(AssertableInertia $page) => $page
            ->component('Dashboard')
            ->where('user.data.id', $user->id)
            ->where('user.data.name', $user->name)
            ->where('user.data.email', $user->email)
            ->where('user.data.credits_remaining', $user->credits_remaining)
            ->where('user.data.is_admin', $user->isAdmin())
            ->where('user.data.roles.0.id', $role->id)
            ->where('user.data.roles.0.name', $role->name)
            ->where('user.data.roles.0.slug', $role->slug)
            ->has('appVersion')
            ->has('phpVersion')
    );
});

it('redirects unauthenticated user to login', function () {
    $this->get('/dashboard')->assertRedirect('/login');
});