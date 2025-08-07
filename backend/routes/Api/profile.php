<?php


use App\Http\Controllers\API\User\UserProfileController;

Route::middleware('auth:sanctum')->prefix('/users')->group(function () {
    Route::get('/profile/{profileId}', [UserProfileController::class, 'show']);
    Route::post('/profile/create', [UserProfileController::class, 'create']);
    Route::post('/profile/update', [UserProfileController::class, 'update']);
    Route::post('/profile/visibility', [UserProfileController::class, 'changeVisibility']);
    Route::post('/profile/phone-number/verify', [UserProfileController::class, 'sendPhoneVerificationCode']);
    Route::post('/profile/phone-number/verify-code', [UserProfileController::class, 'verifyPhoneNumber']);
});
