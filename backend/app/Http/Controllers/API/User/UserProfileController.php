<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Services\User\Interfaces\UserProfileServiceInterface;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{
    use AuthorizesRequests;
    protected UserProfileServiceInterface $userProfileService;

    public function __construct(UserProfileServiceInterface $userProfileService)
    {
        $this->userProfileService = $userProfileService;
    }
    /**
     * Display the user profile.
     *
     * @return JsonResponse
     */
    public function show()
    {
        $user = Auth::user();
        $userProfile = $user->userProfile()->first();
        $this->authorize('viewOwnProfile', $userProfile);

        try {
            return response()->json([
                'userProfile' => $userProfile
            ]);
        } catch (Exception $exception) {
            return response()->json([
                'error' => 'Failed to retrieve user profile',
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    /**
     * Display the user profile by user ID.
     *
     * @param int $profileId
     * @return JsonResponse
     */
    public function showOther(int $profileId)
    {
        $userId = Auth::id();
        $userProfile = $this->userProfileService->getUserProfile($profileId);
        $this->authorize('viewUserProfile', $userProfile);

        try {
            return response()->json(['userProfile' => $userProfile]);
        } catch (Exception $exception) {
            return response()->json([
                'error' => 'Failed to retrieve user profile',
                'message' => $exception->getMessage()
            ], 500);
        }
    }
    //create a new profile
    /**
     * Create a new user profile.
     *
     * @return JsonResponse
     */
    public function create(Request $request)
    {
        $user = Auth::user();
        //validate request data
        $request->validate([
            'date_of_birth' => 'nullable|date',
            'bio' => 'nullable|string|max:1000',
            'gender' => 'nullable|in:male,female,other',
            'mobile' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
        ]);
        $this->authorize('createUserProfile', $user);
        try {
            $this->userProfileService->createOrUpdateGeneralInfo($user, $request->all());
            return response()->json(['message' => 'User profile created successfully']);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Failed to create user profile: ',
                'message' =>$e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the user profile.
     *
     * @return JsonResponse
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $profile = $user->userProfile()->first();
        //validate request data
        $request->validate([
            'date_of_birth' => 'nullable|date',
            'bio' => 'nullable|string|max:1000',
            'mobile' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
        ]);
        $this->authorize('updateUserProfile', $profile);
        try {
            $this->userProfileService->createOrUpdateGeneralInfo($user, $request->all());
            // Logic to update the user profile
            return response()->json(['message' => 'User profile updated successfully']);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Failed to update user profile: ',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    /**
     * Update the visibility of the user profile.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function changeVisibility(Request $request)
    {
        $user = Auth::user();
        $profile = $user->userProfile()->first();
        //validate request data
        $request->validate([
            'visibility' => 'required|in:public,private,friends_only',
        ]);
        $this->authorize('updateUserProfile', $profile);
        try {
            $this->userProfileService->updateVisibility($user, $request->visibility);
            return response()->json(['message' => 'User profile visibility updated to ' . $request->visibility]);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Failed to update user profile visibility: ',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
