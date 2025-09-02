<?php

use App\Http\Controllers\Api\V1\ContentController;
use App\Http\Controllers\Api\V1\RoleController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {

    Route::middleware('credits')->group(function () {
        Route::apiResource('users', UserController::class)->except(['create', 'edit']);
        Route::apiResource('content', ContentController::class)->only(['index']);
    });

   
    Route::middleware('admin')->group(function () {
        Route::apiResource('roles', RoleController::class)->except(['create', 'edit']);
        Route::apiResource('users', UserController::class)->only(['store', 'update', 'destroy']);
    });
});