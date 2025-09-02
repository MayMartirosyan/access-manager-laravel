<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\AuthService;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class LoginController extends Controller
{
    protected AuthService $auth;

    public function __construct(AuthService $auth)
    {
        $this->auth = $auth;
    }

    public function create()
    {
        return Inertia::render('Auth/Login');
    }

    public function login(LoginRequest $request)
    {
        if (!Auth::guard('web')->attempt($request->only('email', 'password'))) {
            return Inertia::render('Auth/Login', [
                'errors' => ['email' => 'Неверные данные для входа'],
            ]);
        }

        $user = Auth::guard('web')->user();
        Auth::guard('web')->login($user);

        $token = $this->auth->createToken($user, 'web');

        return Inertia::render('Auth/Login', [
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

    public function logout()
    {
        $user = Auth::guard('web')->user();
        if ($user) {
            $this->auth->revokeTokens($user);
            Auth::guard('web')->logout();
        }

        return redirect()->route('login');
    }
}