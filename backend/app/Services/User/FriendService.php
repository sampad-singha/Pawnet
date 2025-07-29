<?php
namespace App\Services\User;

use App\Models\Friend;
use App\Repositories\FriendRepository;
use App\Repositories\UserRepository;
use App\Services\User\Interfaces\FriendServiceInterface;
use Illuminate\Database\Eloquent\Collection;

class FriendService implements FriendServiceInterface
{
    protected FriendRepository $friendRepository;
    protected UserRepository $userRepository;

    public function __construct(FriendRepository $friendRepository, UserRepository $userRepository)
    {
        $this->friendRepository = $friendRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Get all accepted friends of a user (mutual friendships).
     *
     * @param int $userId
     * @return Collection
     */
    public function getAllFriends(int $userId): Collection
    {
        return $this->friendRepository->getAllFriends($userId);
    }
    public function sendFriendRequest(int $userId, int $friendId): ?Friend
    {
        // Check if any relationship already exists (pending, accepted, or blocked)
        if ($this->friendRepository->doesFriendshipExist($userId, $friendId)) {
            return null; // Relationship already exists in any form (pending, accepted)
        }
        if ($userId === $friendId)
        {
            return null; // Cannot send a friend request to oneself
        }

        // Otherwise, create the friendship (pending status)
        return $this->friendRepository->createFriendship($userId, $friendId);
    }

    public function acceptFriendRequest(int $userId, int $friendId): bool
    {
        $status = $this->friendRepository->getStatus($friendId , $userId);
        if ($status === 'pending') {
            return $this->friendRepository->updateStatus($userId, $friendId, 'accepted');
        }
        return false; // Cannot accept if the status is not pending
    }
    public function rejectFriendRequest(int $userId, int $friendId): bool
    {
        $status = $this->friendRepository->getStatus($friendId , $userId);
        if ($status === 'pending') {
            return $this->friendRepository->deleteFriend($userId, $friendId);
            //Follower logic later
        }
        return false; // Cannot reject if the status is not pending
    }

    public function unFriend(int $userId, int $friendId): bool
    {
        $status = $this->friendRepository->getStatus($userId, $friendId) ?? $this->friendRepository->getStatus($friendId, $userId);
        if ($status == 'accepted') {
            return $this->friendRepository->deleteFriend($userId, $friendId);
        }
        return false; // Cannot unfriend if the status is not accepted
    }

    public function getPendingRequests(int $userId): Collection
    {
        return $this->friendRepository->getFriendsByStatus($userId, 'received');
    }

    public function getSentRequests(int $userId): Collection
    {
        $collection =  $this->friendRepository->getFriendsByStatus($userId, 'sent');
        return $collection->map(function ($friendRequest) {
            // Remove 'user_id' and 'updated_at' from the request
            unset($friendRequest->user_id, $friendRequest->updated_at);

            // Rename 'created_at' to 'requested_at' to indicate when the request was made
            $friendRequest->requested_at = $friendRequest->created_at;
            unset($friendRequest->created_at); // Remove original 'created_at'

            // Optionally, remove 'status' if not needed
            unset($friendRequest->status); // Remove status, as it's not needed in the response

            return $friendRequest;
        });
    }
}
