<?php
namespace App\Services\User;

use App\Models\User;
use App\Models\UserProfile;
use App\Repositories\Interfaces\UserProfileRepositoryInterface;
use App\Services\User\Interfaces\UserProfileServiceInterface;


class UserProfileService implements UserProfileServiceInterface
{
    protected UserProfileRepositoryInterface $userProfileRepository;

    public function __construct(UserProfileRepositoryInterface $userProfileRepository)
    {
        $this->userProfileRepository = $userProfileRepository;
    }
    /**
     * Get the user profile by user ID.
     *
     * @param int $userId
     * @return UserProfile|null
     */
    public function getUserProfile(int $userId): ?UserProfile
    {
        return UserProfile::where('user_id', $userId)->first();
    }

    /**
     * Update general information of the user profile.
     *
     * @param User $user
     * @param array $data
     * @return UserProfile
     */
    public function createOrUpdateGeneralInfo(User $user, array $data): UserProfile
    {
        $profile = UserProfile::where('user_id', $user->id)->first();
        if (!$profile) {
            $profile = $this->userProfileRepository->create($user->id, $data);
        } else {
            $profile = $this->userProfileRepository->update($profile->id, $data);
        }

        return $profile;
    }

    /**
     * Update visibility settings of the user profile.
     *
     * @param User $user
     * @param string $visibility
     * @return UserProfile
     */
    public function updateVisibility(User $user, string $visibility): UserProfile
    {
        $profile = $this->getUserProfile($user->id);
        if ($profile) {
            $profile['visibility'] = $visibility;
            $profile->save();
        }

        return $profile;
    }
}
