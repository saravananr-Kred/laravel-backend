<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\LicenseController;
use App\Http\Controllers\Api\UserDetailController;
use App\Http\Controllers\Api\TaskController;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password/send-otp', [AuthController::class, 'sendResetOtp']);
Route::post('/forgot-password/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/forgot-password/reset', [AuthController::class, 'resetPassword']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('users', UserController::class);
    Route::apiResource('tasks', TaskController::class);
    Route::put('/tasks/status/{id}', [TaskController::class, 'updateStatus']);
    Route::post('/search-user-task', [UserController::class, 'searchUserAndTasks']);
    
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    Route::get('/user-details', [UserDetailController::class, 'index']);
    Route::post('/user-details', [UserDetailController::class, 'store']);
    Route::get('/users/{id}/task', [UserController::class, 'getUserTasks']);
    Route::get('/user/{id}/license', [LicenseController::class, 'show']);
    Route::post('/user/{id}/license', [LicenseController::class, 'store']);
    Route::put('/user/{uid}/license/{id}', [LicenseController::class, 'update']);
    Route::delete('/user/license/{id}', [LicenseController::class, 'destroy']);
    Route::get('/user-details/{id}', [UserDetailController::class, 'show']);
    Route::put('/user-details/{id}', [UserDetailController::class, 'update']);
    Route::delete('/user-details/{id}', [UserDetailController::class, 'destroy']);
});
