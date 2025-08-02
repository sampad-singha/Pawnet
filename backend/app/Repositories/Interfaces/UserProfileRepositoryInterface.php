<?php

namespace App\Repositories\Interfaces;

use App\Models\UserProfile;

interface UserProfileRepositoryInterface
{
    /**
     * Create a new user profile.
     *
     * @param int $userId
     * @param array $data
     * @return UserProfile
     */
    public function create(int $userId, array $data): UserProfile;

    /**
     * Update an existing user profile.
     *
     * @param int $id
     * @param array $data
     * @return UserProfile
     */
    public function update(int $id, array $data): UserProfile;

}
