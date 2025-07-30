<?php

namespace App\Policies;

use App\Models\Friend;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FriendPolicy
{
    public function sendFriendRequest(User $user, User $friend): Response
    {
        // A user cannot send a request to themselves
        if ($user->id === $friend->id) {
            return Response::deny('You cannot send a friend request to yourself.');
        }

        // A user cannot send a request to someone they are already friends with
        $can = !Friend::where(function ($query) use ($user, $friend) {
            $query->where(function ($q) use ($user, $friend) {
                $q->where('user_id', $user->id)
                    ->where('friend_id', $friend->id);
            })
                ->orWhere(function ($q) use ($user, $friend) {
                    $q->where('user_id', $friend->id)
                        ->where('friend_id', $user->id);
                });
        })->whereIn('status', ['pending', 'accepted'])->exists();

        return $can ? Response::allow() : Response::deny('You can not send request to this user.');
    }

    /**
     * Determine if the user can cancel a friend request.
     */
    public function cancelFriendRequest(User $user, User $friend): Response
    {
        // The user can only cancel a pending request they sent
        $can = Friend::where('user_id', $user->id)
            ->where('friend_id', $friend->id)
            ->where('status', 'pending')
            ->exists();
        return $can ? Response::allow() : Response::deny('You can not cancel this request.');
    }

    /**
     * Determine if the user can accept a friend request.
     */
    public function acceptFriendRequest(User $user, User $friend): Response
    {
        // The user can only accept a pending request where they are the recipient
        $can = Friend::where('user_id', $friend->id)
            ->where('friend_id', $user->id)
            ->where('status', 'pending')
            ->exists();
        return $can ? Response::allow() : Response::deny('You can only accept a pending friend request from this user.');
    }

    /**
     * Determine if the user can reject a friend request.
     */
    public function rejectFriendRequest(User $user, User $friend): Response
    {
        // The user can only reject a pending request where they are the recipient
        $can = Friend::where('user_id', $friend->id)
            ->where('friend_id', $user->id)
            ->where('status', 'pending')
            ->exists();
        return $can ? Response::allow() : Response::deny('You can only reject a pending friend request from this user.');
    }

    /**
     * Determine if the user can unfriend (remove a friend) another user.
     */
    public function unFriend(User $user, User $friend): Response
    {
        // The user can only unfriend someone if they are currently friends (status = 'accepted')
        $can = Friend::where(function ($query) use ($user, $friend) {
            $query->where(function ($q) use ($user, $friend) {
                // Check if the user is 'user_id' and friend is 'friend_id'
                $q->where('user_id', $user->id)
                    ->where('friend_id', $friend->id);
            })
                ->orWhere(function ($q) use ($user, $friend) {
                    // Check if the friend is 'user_id' and user is 'friend_id'
                    $q->where('user_id', $friend->id)
                        ->where('friend_id', $user->id);
                });
        })
            ->where('status', 'accepted')  // Ensure the status is 'accepted'
            ->exists();

        return $can ? Response::allow() : Response::deny('You can only unfriend users you are currently friends with.');
    }
}
