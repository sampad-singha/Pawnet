<?php

use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\Auth\FacebookAuthController;
use App\Http\Controllers\API\Auth\GoogleAuthController;


Route::post('/register', [AuthController::class, 'register']);
Route::middleware('throttle:5,1')->post('/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::get('/user/verify-token', [AuthController::class, 'verifyToken']);
    Route::post('/user/set-password', [AuthController::class, 'setPassword']);
    Route::post('/user/change-password', [AuthController::class, 'changePassword']);
    Route::post('/refresh-token', [AuthController::class, 'refreshToken']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/logout-all', [AuthController::class, 'logoutAll']);
});

Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.reset');



//Google Authentication Routes
Route::prefix('auth/google')->group(function () {
    Route::get('/redirect', [GoogleAuthController::class, 'redirect']);
    Route::get('/callback', [GoogleAuthController::class, 'callback']);
});

//Facebook Authentication Routes
Route::prefix('auth/facebook')->group(function () {
    Route::get('/redirect', [FacebookAuthController::class, 'redirect']);
    Route::get('/callback', [FacebookAuthController::class, 'callback']);
});
