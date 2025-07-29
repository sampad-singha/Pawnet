<?php
namespace App\Services\User\Interfaces;

use App\Models\Friend;
use Illuminate\Database\Eloquent\Collection;

interface FriendServiceInterface
{
    /**
     * Get all accepted friends of a user (mutual friendships).
     *
     * @param int $userId
     * @return Collection
     */
    public function getAllFriends(int $userId): Collection;

    /**
     * Send a friend request.
     *
     * @param int $userId
     * @param int $friendId
     * @return Friend|null
     */
    public function sendFriendRequest(int $userId, int $friendId): ?Friend;

    /**
     * Accept a friend request.
     *
     * @param int $userId
     * @param int $friendId
     * @return bool
     */
    public function acceptFriendRequest(int $userId, int $friendId): bool;

    /**
     * Reject a friend request.
     *
     * @param int $userId
     * @param int $friendId
     * @return bool
     */
    public function rejectFriendRequest(int $userId, int $friendId): bool;

    /**
     * Unfriend a user.
     *
     * @param int $userId
     * @param int $friendId
     * @return bool
     */
    public function unFriend(int $userId, int $friendId): bool;

    /**
     * Get all pending friend requests for a user.
     *
     * @param int $userId
     * @return Collection
     */
    public function getPendingRequests(int $userId): Collection;

}

