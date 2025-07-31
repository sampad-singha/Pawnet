<?php

namespace App\Repositories;

use App\Models\UserProfile;
use App\Repositories\Interfaces\UserProfileRepositoryInterface;

class UserProfileRepository Implements UserProfileRepositoryInterface
{
    public function create(int $userId, array $data): UserProfile
    {
        //create new user profile with the given user ID and data 'user_id',
        $profile = UserProfile::create([
            'user_id' => $userId,
            'bio' => $data['bio'] ?? '',
            'date_of_birth' => $data['date_of_birth'],
            'gender' => $data['gender'] ?? null,
            'phone_number' => $data['phone_number'] ?? '',
            'address' => $data['address'] ?? '',
            'city' => $data['city'] ?? '',
            'state' => $data['state'] ?? '',
            'country' => $data['country'] ?? '',
            'visibility' => $data['visibility'] ?? 'public',
        ]);

        $profile->save();
        return $profile;
    }
    public function update(int $id, array $data): UserProfile
    {
        //find the user profile by user ID and update it with the given data
        $profile = UserProfile::findOrFail($id);
        $profile->update($data);

        return $profile->fresh();
    }

    public function updatePhoneNumberStatus(int $id, bool $status): bool
    {
        //find the user profile by user ID and update its phone number status
        $profile = UserProfile::findOrFail($id);
        $profile->phone_number_verified = $status;

        return $profile->save();
    }

    public function updateVisibility(int $id, string $visibility): bool
    {
        //find the user profile by user ID and update its visibility
        $profile = UserProfile::findOrFail($id);
        $profile->visibility = $visibility;

        return $profile->save();
    }
}
