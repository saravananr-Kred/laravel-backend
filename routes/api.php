<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\LicenseController;
use App\Http\Controllers\Api\UserDetailController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\CommentsController;

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

    //Task
    Route::apiResource('tasks', TaskController::class);
    Route::get('/users/{id}/task', [UserController::class, 'getUserTasks']);
    Route::put('/tasks/status/{id}', [TaskController::class, 'updateStatus']);
    Route::post('/search-user-task', [UserController::class, 'searchUserAndTasks']);
    
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    //Task comments
    Route::get('/tasks/{tid}/comments', [CommentsController::class, 'show']);
    Route::post('/tasks/{tid}/comments', [CommentsController::class, 'store']);
    Route::put('/tasks/comments/{id}', [CommentsController::class, 'update']);
    Route::delete('/tasks/comments/{id}', [CommentsController::class, 'destroy']);

    //License
    Route::get('/user/{id}/license', [LicenseController::class, 'show']);
    Route::post('/user/{id}/license', [LicenseController::class, 'store']);
    Route::put('/user/{uid}/license/{id}', [LicenseController::class, 'update']);
    Route::delete('/user/license/{id}', [LicenseController::class, 'destroy']);
    
    //UserDetail
    Route::get('/user-details', [UserDetailController::class, 'index']);
    Route::get('/user-details/{id}', [UserDetailController::class, 'show']);
    Route::post('/user-details', [UserDetailController::class, 'store']);
    Route::put('/user-details/{id}', [UserDetailController::class, 'update']);
    Route::delete('/user-details/{id}', [UserDetailController::class, 'destroy']);
});
