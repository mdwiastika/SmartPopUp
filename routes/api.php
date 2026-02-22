<?php

use Illuminate\Support\Facades\Route;


Route::group(['middleware' => 'api'], function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('/register', [App\Http\Controllers\AuthController::class, 'register']);
        Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);
        Route::post('/refresh', [App\Http\Controllers\AuthController::class, 'refresh']);
        Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout']);
    });

    Route::group(['middleware' => 'auth:api'], function () {
        Route::apiResource('users', App\Http\Controllers\UserController::class);
    });
});
