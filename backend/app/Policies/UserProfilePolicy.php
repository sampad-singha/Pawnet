<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Auth\Access\Response;

class UserProfilePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function viewOwnProfile(User $user, UserProfile $userProfile): Response
    {
        if( $user->id !== $userProfile->user_id) {
            return Response::deny('This profile does not belong to you.'); // User can view their own profile
        }
        return Response::allow(); // Allow viewing if the user owns the profile
    }

    public function viewUserProfile(User $user, UserProfile $userProfile): Response
    {
        // Will add logic to check if user is blocked


        // Allow viewing if the user owns the profile or if the profile is public
        if ($user->id === $userProfile->user_id || $userProfile->visibility === 'public') {
            return Response::allow();
        }
        $friend = User::find($userProfile->user_id);
        // allow for friends
        if ($user->isFriendsWith($friend)) {
            return Response::allow();
        }
        return Response::deny('You do not have permission to view this profile.');
    }

    /**
     * Determine whether the user can create models.
     */
    public function createUserProfile(User $user): Response
    {
        // check if user already has a profile
        $hasProfile = UserProfile::where('user_id', $user->id)->exists();
        if ($hasProfile) {
            return Response::deny('You already created your profile.');
        } else {
            // allow creation if no profile exists
            return Response::allow();
        }
    }

    /**
     * Determine whether the user can update the model.
     */
    public function updateUserProfile(User $user, UserProfile $userProfile): Response
    {
        if ($user->id !== $userProfile->user_id) {
            return Response::deny('This profile does not belong to you.'); // User can update their own profile
        }
        $exists = UserProfile::where('user_id', $user->id)->exists();
        if (!$exists) {
            return Response::deny('You do not have a profile to update.');
        }
        return Response::allow(); // Allow update if the user owns the profile
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, UserProfile $userProfile): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, UserProfile $userProfile): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, UserProfile $userProfile): bool
    {
        return false;
    }
}
