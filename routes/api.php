<?php

use App\Http\Controllers\API\V1\AuthController;
use App\Http\Controllers\API\V1\ChatController;
use App\Http\Controllers\API\V1\TaskController;
use App\Http\Controllers\API\V1\UserController;
use Illuminate\Support\Facades\Route;


Route::prefix("users")->group(function () {

    // User auth
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);
    //
    Route::post('/request-otp-code', [AuthController::class, 'requestOTPCode']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
    //
    Route::middleware('auth:api')->group(function () {
        // 
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::post('/auth/refresh-token', [AuthController::class, 'refreshToken']);

        // User information 
        Route::get('/{user_id}', [UserController::class, 'show']);
        Route::put('/{user_id}', [UserController::class, 'update']);
        Route::delete('/{user_id}', [UserController::class, 'destroy']);

        // User tasks
        Route::prefix('/{user_id}/tasks')->group(function () {
            //
            Route::get('/', [TaskController::class, 'index']);
            Route::post('/', [TaskController::class, 'store']);
            Route::put('/{task_id}', [TaskController::class, 'update']);
            Route::get('/{task_id}', [TaskController::class, 'show']);
            Route::delete('/{task_id}', [TaskController::class, 'destroy']);
        });

        // AI chatbot
        Route::get('/{user_id}/chat', [ChatController::class, 'show']);
        Route::post('/{user_id}/chat/send', [ChatController::class, 'sendMessage']);
        Route::delete('/{user_id}/chat', [ChatController::class, 'deleteChat']);
    });
});
