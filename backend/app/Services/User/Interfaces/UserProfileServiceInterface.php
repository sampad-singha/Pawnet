<?php
//basic interface template
namespace App\Services\User\Interfaces;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;

interface UserProfileServiceInterface
{
    /**
     * Get the user profile by user ID.
     *
     * @param int $userId
     * @return UserProfile|null
     */
    public function getUserProfile(int $userId): ?UserProfile;
    /**
     * Update general information of the user profile.
     *
     * @param User $user
     * @param array $data
     * @return UserProfile
     */
    public function createOrUpdateGeneralInfo(User $user, array $data): UserProfile;

    /**
     * Update visibility settings of the user profile.
     *
     * @param User $user
     * @param string $visibility
     * @return UserProfile
     */
    public function updateVisibility(User $user, string $visibility): UserProfile;


    public function sendPhoneNumberVerificationCode(UserProfile $profile,string $phoneNumber): bool;

    public function verifyPhoneNumber(User $user,string $phoneNumber, int $code): bool;
}
