<?php

use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\Auth\FacebookAuthController;
use App\Http\Controllers\API\Auth\GoogleAuthController;
use App\Http\Controllers\API\User\FriendController;
use App\Http\Controllers\API\User\UserProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
//Route::post('/login', [AuthController::class, 'login']);
Route::middleware('throttle:5,1')->post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
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
Route::prefix('auth/facebook')->group(function () {
    Route::get('/redirect', [FacebookAuthController::class, 'redirect']);
    Route::get('/callback', [FacebookAuthController::class, 'callback']);
});

Route::middleware('auth:sanctum')->prefix('/friends')->group(function () {
    Route::get('/', [FriendController::class, 'getFriends']);
    Route::post('/send-request/{friendId}', [FriendController::class, 'sendFriendRequest']);
    Route::post('/cancel-request/{friendId}', [FriendController::class, 'cancelFriendRequest']);
    Route::post('/accept-request/{friendId}', [FriendController::class, 'acceptFriendRequest']);
    Route::post('/reject-request/{friendId}', [FriendController::class, 'rejectFriendRequest']);
    Route::post('/unfriend/{friendId}', [FriendController::class, 'deleteFriend']);
    Route::get('/request/pending', [FriendController::class, 'getPendingRequests']);
    Route::get('/request/sent', [FriendController::class, 'getSentRequests']);
});

Route::middleware('auth:sanctum')->prefix('/users')->group(function () {
//    Route::get('/profile', [UserProfileController::class, 'show']);
    Route::get('/profile/{profileId}', [UserProfileController::class, 'show']);
    Route::post('/profile/create', [UserProfileController::class, 'create']);
    Route::post('/profile/update', [UserProfileController::class, 'update']);
    Route::post('/profile/visibility', [UserProfileController::class, 'changeVisibility']);
    Route::post('/profile/phone-number/verify', [UserProfileController::class, 'sendPhoneVerificationCode']);
    Route::post('/profile/phone-number/verify-code', [UserProfileController::class, 'verifyPhoneNumber']);
});
