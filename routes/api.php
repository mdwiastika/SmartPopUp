<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DifficultyController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\SubscriptionUserController;
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
            Route::apiResource('difficulies', DifficultyController::class)->only(['index', 'show']);
            Route::apiResource('subscriptions', SubscriptionController::class)->only(['index', 'show']);
            Route::apiResource('subscription-users', SubscriptionUserController::class);
            Route::get('/user-subscription/check-user', [SubscriptionUserController::class, 'checkUserSubscription']);
            Route::apiResource('materials', MaterialController::class)->only(['index', 'show']);


            Route::middleware('role:admin|superadmin')->group(function () {
                Route::apiResource('grades', GradeController::class)->only(['store', 'update', 'destroy']);
                Route::apiResource('difficulies', DifficultyController::class)->only(['store', 'update', 'destroy']);
                Route::apiResource('subscriptions', SubscriptionController::class)->only(['store', 'update', 'destroy']);
                Route::apiResource('subscription-users', SubscriptionUserController::class)->only(['store', 'update', 'destroy']);
                Route::apiResource('materials', MaterialController::class)->only(['store', 'update', 'destroy']);
            });
        });
    });
});
