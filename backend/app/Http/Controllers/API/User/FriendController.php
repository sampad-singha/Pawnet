<?php
namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\User\Interfaces\FriendServiceInterface;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{
    use AuthorizesRequests;
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
    public function sendFriendRequest(int $friendId)
    {
        $user = Auth::user();
        $friend = User::find($friendId);
        $this->authorize('sendFriendRequest', $friend);

        try {
            $this->friendService->sendFriendRequest($user->id, $friendId);

            return response()->json([
                'message' => 'Friend request sent successfully.',
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel a pending friend request.
     *
     * @param string $friendId
     * @return JsonResponse
     */
    public function cancelFriendRequest(string $friendId)
    {
        $user = Auth::user();
        $friend = User::find($friendId);

        $this->authorize('cancelFriendRequest', $friend);
        try {
            $this->friendService->cancelFriendRequest($user->id, $friendId);
            return response()->json([
                'message' => 'Friend request cancelled successfully.'
            ]);
        } catch (Exception $e) {
            return  response()->json([
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Accept a pending friend request.
     *
     * @param int $friendId
     * @return JsonResponse
     */
    public function acceptFriendRequest(int $friendId): JsonResponse
    {
        $user = Auth::user();
        $friend = User::find($friendId);
        $this->authorize('acceptFriendRequest', $friend);

        try {
            $this->friendService->acceptFriendRequest($user->id, $friendId);
            return response()->json([
                'message' => 'Friend request accepted.'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ], 500);
        }
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
        $friend = User::find($friendId);
        $this->authorize('rejectFriendRequest', $friend);

        try {
            $this->friendService->rejectFriendRequest($userId, $friendId);
            return response()->json([
                'message' => 'Friend request rejected.'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ], 500);
        }

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
        $friend = User::find($friendId);
        $this->authorize('unFriend', $friend);

        try {
            $this->friendService->unFriend($userId, $friendId);

            return response()->json([
                'message' => 'Friendship deleted successfully.'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ], 500);
        }
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
}
