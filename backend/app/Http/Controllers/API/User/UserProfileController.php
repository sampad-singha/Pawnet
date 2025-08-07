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
     * Display the user profile by user ID.
     *
     * @param int $profileId
     * @return JsonResponse
     */
    public function show(int $profileId)
    {
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
        $data = $request->validate([
            'date_of_birth' => 'nullable|date',
            'bio' => 'nullable|string|max:1000',
            'gender' => 'nullable|in:male,female,other',
            'phone_number' => 'nullable|string|max:15',
            'country_iso2' => 'string|max:2',
            'address' => 'nullable|string|max:255',
            'city_id' => 'nullable|string|max:255',
            'state_id' => 'nullable|string|max:255',
            'country_id' => 'string|max:255',
        ]);
        $this->authorize('createUserProfile', $user);
        try {
            $this->userProfileService->createOrUpdateGeneralInfo($user, $data);
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
            'phone_number' => 'nullable|string|max:15',
            'country_iso2' => 'string|max:2',
            'address' => 'nullable|string|max:255',
            'city_id' => 'nullable|string|max:255',
            'state_id' => 'nullable|string|max:255',
            'country_id' => 'string|max:255',
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

    public function sendPhoneVerificationCode()
    {
        $user = Auth::user();
        $profile = $user->userProfile()->first();
        $phoneNumber = $profile->phone_number;
        $this->authorize('updateUserProfile', $profile);
        try {
            $this->userProfileService->sendPhoneNumberVerificationCode($profile, $phoneNumber);
            return response()->json([
                'message' => 'Phone verification code sent successfully',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Failed to send phone verification code.',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function verifyPhoneNumber(Request $request)
    {
        $user = Auth::user();
        $profile = $user->userProfile()->first();
        $phone_number = $profile->phone_number;
        //validate request data
        $request->validate([
            'code' => 'required|integer',
        ]);
        $this->authorize('updateUserProfile', $profile);
        try {
            $this->userProfileService->verifyPhoneNumber($user, $phone_number, $request->code);
            return response()->json(['message' => 'Phone number verified successfully']);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Failed to verify phone number: ',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
