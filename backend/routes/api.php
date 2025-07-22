<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [\App\Http\Controllers\API\Auth\AuthController::class, 'register']);
Route::post('/login', [\App\Http\Controllers\API\Auth\AuthController::class, 'login']);
Route::post('/logout', [\App\Http\Controllers\API\Auth\AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/refresh-token', [\App\Http\Controllers\API\Auth\AuthController::class, 'refreshToken'])->middleware('auth:sanctum');
