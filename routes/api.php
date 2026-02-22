<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::middleware('api')->group(function () {
    Route::prefix('v1')->group(function () {
        Route::prefix('auth')->group(function () {
            Route::post('/register', [AuthController::class, 'register']);
            Route::post('/login', [AuthController::class, 'login']);
            Route::post('/refresh', [AuthController::class, 'refresh']);
            Route::post('/logout', [AuthController::class, 'logout']);
        });

        Route::middleware('auth:api')->group(function () {
            Route::apiResource('users', UserController::class);

            Route::apiResource('grades', GradeController::class)->only(['index', 'show']);
            Route::middleware('role:admin|superadmin')->group(function () {
                Route::apiResource('grades', GradeController::class)->only(['store', 'update', 'destroy']);
            });
        });
    });
});
