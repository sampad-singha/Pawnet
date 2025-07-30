<?php
namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\User\Interfaces\FriendServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{
    protected FriendServiceInterface $friendService;

    public function __construct(FriendServiceInterface $friendService)
    {
        $this->friendService = $friendService;
    }

    /**
     * Get all accepted friends (mutual friendships) of a user.
     *
     * @return JsonResponse
     */
    public function getFriends(): JsonResponse
    {
        $userId = Auth::user()->id;
        $friends = $this->friendService->getAllFriends($userId);

        return response()->json($friends);
    }

    /**
     * Send a friend request to another user.
     *
     * @param int $friendId
     * @return JsonResponse
     */
    public function sendFriendRequest(int $friendId): JsonResponse
    {
        $user = Auth::user();
        $friend = User::find($friendId);
        //authorization check through policy

        if ($user->cannot('sendFriendRequest', $friend)) {
            return response()->json([
                'message' => 'You cannot send a friend request to this user.'
            ], 403);
        }
        $friend = $this->friendService->sendFriendRequest($user->id, $friendId);

        if ($friend === null) {
            return response()->json([
                'message' => 'Friend request already sent or users are already friends.'
            ], 400);
        }

        return response()->json($friend, 201);
    }

    /**
     * Accept a pending friend request.
     *
     * @param int $friendId
     * @return JsonResponse
     */
    public function acceptFriendRequest(int $friendId): JsonResponse
    {
        $userId = Auth::user()->id;
        $operation = $this->friendService->acceptFriendRequest($userId, $friendId);

        if (!$operation) {
            return response()->json([
                'message' => 'Friend request not found or already accepted.'
            ], 404);
        }

        return response()->json([
            'message' => 'Friend request accepted.'
        ]);
    }

    /**
     * Reject a pending friend request.
     *
     * @param int $friendId
     * @return JsonResponse
     */
    public function rejectFriendRequest(int $friendId): JsonResponse
    {
        $userId = Auth::user()->id;
        $operation = $this->friendService->rejectFriendRequest($userId, $friendId);

        if (!$operation) {
            return response()->json([
                'message' => 'Friend request not found or already rejected.'
            ], 404);
        }

        return response()->json([
            'message' => 'Friend request rejected.'
        ]);
    }

    /**
     * Remove a friend (unfriend).
     *
     * @param int $friendId
     * @return JsonResponse
     */
    public function deleteFriend(int $friendId): JsonResponse
    {
        $userId = Auth::user()->id;
        $operation = $this->friendService->unFriend($userId, $friendId);

        if (!$operation) {
            return response()->json([
                'message' => 'Friendship not found or already deleted.'
            ], 404);
        }

        return response()->json([
            'message' => 'Friendship deleted successfully.'
        ]);
    }

    /**
     * Get all pending friend requests for the user.
     *
     * @return JsonResponse
     */
    public function getPendingRequests(): JsonResponse
    {
        $userId = Auth::user()->id;
        $pendingRequests = $this->friendService->getPendingRequests($userId);

        return response()->json($pendingRequests);
    }

    /**
     * Get all sent friend requests by the user.
     *
     * @return JsonResponse
     */
    public function getSentRequests(): JsonResponse
    {
        $userId = Auth::user()->id;
        $sentRequests = $this->friendService->getSentRequests($userId);

        return response()->json($sentRequests);
    }

    public function cancelFriendRequest(string $friendId)
    {

    }
}
