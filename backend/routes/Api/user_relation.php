<?php


use App\Http\Controllers\API\User\FriendController;

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
