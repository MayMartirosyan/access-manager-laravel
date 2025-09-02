<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class RegisterController extends Controller
{
    protected AuthService $auth;

    public function __construct(AuthService $auth)
    {
        $this->auth = $auth;
    }

    public function create()
    {
        return Inertia::render('Auth/Register');
    } 

    public function store(RegisterRequest $request)
    {
        $user = $this->auth->register(
            $request->name,
            $request->email,
            $request->password
        );

        event(new Registered($user));
        Auth::login($user);

        $token = $this->auth->createToken($user, 'web');

        return Inertia::render('Auth/Register', [
            'token' => $token,
            'redirect' => route('dashboard'),
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'is_admin' => $user->isAdmin(),
                'credits_remaining' => $user->credits_remaining,
            ],
        ]);
    }
}