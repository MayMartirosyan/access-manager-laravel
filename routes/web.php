<?php

use App\Http\Controllers\RedirectController;
use App\Http\Controllers\Web\Content\ContentController;
use App\Http\Controllers\Web\Auth\RegisterController;
use App\Http\Controllers\Web\Auth\LoginController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Middleware\RedirectIfAuthenticated;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::get('/', [RedirectController::class, 'handleRedirect']);

Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
Route::get('/login', [LoginController::class, 'create'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.store');


Route::middleware('auth:web')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/content', [ContentController::class, 'index'])->name('content');
    Route::middleware('admin')->group(function () {
        Route::get('/users', fn() => Inertia::render('Users/Index'))->name('users.index');
        Route::get('/roles', fn() => Inertia::render('Roles/Index'))->name('roles.index');
    });
});