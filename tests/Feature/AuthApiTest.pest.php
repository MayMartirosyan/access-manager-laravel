<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Inertia\Testing\AssertableInertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Testing\RefreshDatabase;


uses(RefreshDatabase::class);

it('registers and returns token', function () {

   Role::create([
        'name' => 'Member',
        'slug' => 'member',
    ]);

    $response = $this->post('/register', [
        'name' => 'Test',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);


    $response->assertOk();

    // $response->assertInertia(
    //     fn(AssertableInertia $page) =>
    //     $page
    //         ->component('Auth/Register')
    //         ->has('token')
    //         ->where('redirect', route('dashboard'))
    //         ->has(
    //             'user',
    //             fn($user) =>
    //             $user
    //                 ->has('id')
    //                 ->where('user.data.name', $user->name)
    //                 ->where('user.data.email', $user->email)
    //                 ->where('user.data.is_admin', $user->is_admin)
    //                 ->where('user.data.credits_remaining', 100)
    //         )
    // );

    $response->assertInertia(
        fn(AssertableInertia $page) => $page
            ->component('Auth/Register')
            ->has('token')
            ->where('redirect', url('/dashboard'))
            ->has(
                'user',
                fn($user) => $user
                    ->has('id')
                    ->has('name')
                    ->has('email')
                    ->has('is_admin')
                    ->has('credits_remaining')
            )
    );

    expect(User::where('email', 'test@example.com')->exists())->toBeTrue();
});

it('fails login with bad creds', function () {
    $response = $this->post('/login', [
        'email' => 'no@example.com',
        'password' => 'bad',
    ])->assertOk();

    $response->assertInertia(
        fn(AssertableInertia $page) => $page
            ->component('Auth/Login')
            ->where('errors.email', 'Неверные данные для входа')
    );
});

it('logins and logs out', function () {
    $user = User::factory()->create(['password' => Hash::make('password')]);

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ])->assertOk();

    $response->assertInertia(
        fn(AssertableInertia $page) => $page
            ->component('Auth/Login')
            ->has('token')
            ->where('redirect', url('/dashboard'))
            ->has(
                'user',
                fn($user) => $user
                    ->has('id')
                    ->has('name')
                    ->has('email')
                    ->has('is_admin')
                    ->has('credits_remaining')
            )
    );

    $this->actingAs($user)->post('/logout')->assertRedirect('/login');
});