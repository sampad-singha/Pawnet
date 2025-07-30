<?php

namespace App\Repositories;

use App\Models\Friend;
use App\Repositories\Interfaces\FriendRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class FriendRepository implements FriendRepositoryInterface
{
    public function doesFriendshipExist(int $userId, int $friendId): bool
    {
        // Check for any relationship in either direction (userId -> friendId or friendId -> userId)
        return Friend::where(function ($query) use ($userId, $friendId) {
            // Check both directions: userId -> friendId or friendId -> userId
            $query->where('user_id', $userId)
                ->where('friend_id', $friendId)
                ->orWhere(function ($query) use ($userId, $friendId) {
                    $query->where('user_id', $friendId)
                        ->where('friend_id', $userId);
                });
        })
            ->exists();
    }

    public function getAllFriends(int $userId): Collection
    {
        return Friend::where(function ($query) use ($userId) {
            $query->where('user_id', $userId)
                ->orWhere('friend_id', $userId);
        })
            ->where('status', 'accepted')
            ->get();
    }

    public function createFriendship(int $userId, int $friendId): Friend
    {
        //Status is set to 'pending' by default when creating a new friendship
        return Friend::create([
            'user_id' => $userId,
            'friend_id' => $friendId,
        ]);
    }

    public function getFriendsByStatus(int $userId, string $status): Collection
    {
        if ($status == 'sent') {
            return Friend::where('user_id', $userId)
                ->where('status', 'pending')
                ->with(['friend'])
                ->get();
        } elseif ($status == 'received') {
            return Friend::where('friend_id', $userId)
                ->where('status', 'pending')
                ->with(['user'])
                ->get();
        }
        return Friend::where(function ($query) use ($userId) {
            $query->where('user_id', $userId)
                ->orWhere('friend_id', $userId);
        })
            ->where('status', $status)
            ->get();
    }

    public function getStatus(int $userId, int $friendId): ?string
    {
        $friendship = Friend::where(function ($query) use ($userId, $friendId) {
            $query->where('user_id', $userId)
                ->where('friend_id', $friendId);
        })
        ->first();

        return $friendship ? $friendship->status : null;
    }

    public function updateStatus(int $userId, int $friendId, string $status): bool
    {
        $friendship = Friend::where(function ($query) use ($userId, $friendId) {
            $query->where('user_id', $userId)
                ->where('friend_id', $friendId);
        })
            ->orWhere(function ($query) use ($userId, $friendId) {
                $query->where('user_id', $friendId)
                    ->where('friend_id', $userId);
            })
            ->first();

        if ($friendship) {
            if ($friendship->status === $status) {
                // If the status is already the same, no need to update
                return false;
            }
            $friendship->status = $status;
            $friendship->save();
            return true;
        }

        return false;
    }

    public function deleteFriend(int $userId, int $friendId): bool
    {
        $friendship = Friend::where(function ($query) use ($userId, $friendId) {
            $query->where('user_id', $userId)
                ->where('friend_id', $friendId);
        })
            ->orWhere(function ($query) use ($userId, $friendId) {
                $query->where('user_id', $friendId)
                    ->where('friend_id', $userId);
            })
            ->first();

        if ($friendship) {
            return $friendship->delete();
        }

        return false;
    }
}
