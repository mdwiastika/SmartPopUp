<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DifficultyController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\SubscriptionUserController;
use App\Http\Controllers\UserAnswerController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserInformationController;
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
            Route::get('/my-profile', [UserController::class, 'myProfile']);

            Route::apiResource('grades', GradeController::class)->only(['index', 'show']);
            Route::apiResource('difficulties', DifficultyController::class)->only(['index', 'show']);
            Route::apiResource('subscriptions', SubscriptionController::class)->only(['index', 'show']);
            Route::get('/subscription-users/check-user', [SubscriptionUserController::class, 'checkUserSubscription']);
            Route::apiResource('subscription-users', SubscriptionUserController::class);
            Route::apiResource('materials', MaterialController::class)->only(['index', 'show']);
            Route::apiResource('user-information', UserInformationController::class);
            Route::apiResource('questions', QuestionController::class)->only(['index', 'show']);
            Route::get('/user-answers/history', [UserAnswerController::class, 'history']);
            Route::get('/user-answers', [UserAnswerController::class, 'index']);
            Route::post('/user-answers', [UserAnswerController::class, 'store']);
            Route::get('/user-answers/{id}', [UserAnswerController::class, 'show']);

            Route::middleware('role:admin|superadmin')->group(function () {
                Route::apiResource('grades', GradeController::class)->only(['store', 'update', 'destroy']);
                Route::apiResource('difficulties', DifficultyController::class)->only(['store', 'update', 'destroy']);
                Route::apiResource('subscriptions', SubscriptionController::class)->only(['store', 'update', 'destroy']);
                Route::apiResource('subscription-users', SubscriptionUserController::class)->only(['store', 'update', 'destroy']);
                Route::apiResource('materials', MaterialController::class)->only(['store', 'update', 'destroy']);
                Route::apiResource('questions', QuestionController::class)->only(['store', 'update', 'destroy']);
                Route::post('/questions-import-excel', [QuestionController::class, 'importFromExcel']);
            });
        });
    });
});
