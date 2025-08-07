<?php

namespace App\Repositories;

use App\Models\UserProfile;
use App\Models\Util\Country;
use App\Repositories\Interfaces\UserProfileRepositoryInterface;
use libphonenumber\NumberParseException;
use Propaganistas\LaravelPhone\PhoneNumber;

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
            'city_id' => $data['city_id'] ?? '',
            'state_id' => $data['state_id'] ?? '',
            'country_id' => $data['country_id'] ?? '',
            'visibility' => $data['visibility'] ?? 'public',
        ]);
        $country = Country::find($data['country_id']);
        $phone = new PhoneNumber($profile['phone_number'], $country);

        // Format the number to the E.164 international standard
        $internationalFormat = $phone->formatE164();
        $profile->phone_number = $internationalFormat;

        $profile->save();

        return $profile;
    }
    public function update(int $id, array $data): UserProfile
    {
        //find the user profile by user ID and update it with the given data
        $profile = UserProfile::findOrFail($id);

        //check if phone_number will be updated
        if (isset($data['phone_number']) && $data['phone_number'] !== $profile->phone_number) {
            $data['phone_verified'] = false;
        }

        $profile->update($data);

        return $profile->fresh();
    }

    public function updatePhoneNumberStatus(int $id, bool $status): bool
    {
        //find the user profile by user ID and update its phone number status
        $profile = UserProfile::findOrFail($id);
        $profile->phone_verified = $status;

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
