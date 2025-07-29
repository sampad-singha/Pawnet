<?php

namespace App\Policies;

use App\Models\Friend;
use App\Models\User;

class FriendshipPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }
    /**
     * Determine if the user can send a friend request to another user.
     */
    public function sendFriendRequest(User $user, User $friend): bool
    {
        // A user cannot send a request to themselves
        if ($user->id === $friend->id) {
            return false;
        }

        // A user cannot send a request to someone they are already friends with
        return !Friend::where(function ($query) use ($user, $friend) {
            $query->where('user_id', $user->id)
                ->where('friend_id', $friend->id);
        })->whereIn('status', ['pending', 'accepted'])->exists();
    }

    /**
     * Determine if the user can accept a friend request.
     */
    public function acceptFriendRequest(User $user, User $friend): bool
    {
        // The user can only accept a pending request where they are the recipient
        return Friend::where('user_id', $friend->id)
            ->where('friend_id', $user->id)
            ->where('status', 'pending')
            ->exists();
    }

    /**
     * Determine if the user can reject a friend request.
     */
    public function rejectFriendRequest(User $user, User $friend): bool
    {
        // The user can only reject a pending request where they are the recipient
        return Friend::where('user_id', $friend->id)
            ->where('friend_id', $user->id)
            ->where('status', 'pending')
            ->exists();
    }

    /**
     * Determine if the user can unfriend (remove a friend) another user.
     */
    public function unFriend(User $user, User $friend): bool
    {
        // The user can only unfriend someone if they are currently friends (status = 'accepted')
        return Friend::where('user_id', $user->id)
            ->where('friend_id', $friend->id)
            ->where('status', 'accepted')
            ->exists();
    }
}
