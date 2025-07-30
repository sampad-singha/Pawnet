<?php
namespace App\Repositories\Interfaces;

interface FriendRepositoryInterface
{
    /**
     * Check if a friendship exists between two users (either pending or accepted).
     *
     * @param int $userId
     * @param int $friendId
     * @return bool
     */
    public function doesFriendshipExist(int $userId, int $friendId): bool;
    /**
     * Get all friends of a user (both sides of the relationship must be accepted).
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllFriends(int $userId): \Illuminate\Database\Eloquent\Collection;
    /**
     * Create a friendship between two users.
     *
     * @param int $userId
     * @param int $friendId
     * @param string $status
     * @return \App\Models\Friend
     */
    public function createFriendship(int $userId, int $friendId): \App\Models\Friend;

    /**
     * Get all friends of a user with a specific status.
     *
     * @param int $userId
     * @param string $status
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getFriendsByStatus(int $userId, string $status): \Illuminate\Database\Eloquent\Collection;

    /**
     * Update the status of a friendship.
     *
     * @param int $userId
     * @param int $friendId
     * @param string $status
     * @return bool
     */
    public function updateStatus(int $userId, int $friendId, string $status): bool;
}
