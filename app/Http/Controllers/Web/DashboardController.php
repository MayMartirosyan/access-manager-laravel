<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        return Inertia::render('Dashboard', [
            'user' => new UserResource($user->load('roles')),
            'appVersion' => app()->version(),
            'phpVersion' => PHP_VERSION,
        ]);
    }
}