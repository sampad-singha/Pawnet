<?php

namespace App\Services\User;

use App\Models\User;
use App\Models\UserProfile;
use App\Repositories\Interfaces\UserProfileRepositoryInterface;
use App\Services\User\Interfaces\UserProfileServiceInterface;
use Exception;
use Rinvex\Country\CountryLoader;
use Rinvex\Country\CountryLoaderException;
use Twilio\Rest\Client;
use Propaganistas\LaravelPhone\PhoneNumber;


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
        // logic to modify phone_number before passing
        if (isset($data['phone_number']))
        {
            $phone = new PhoneNumber($data['phone_number'], $data['country_iso2']);
            $internationalFormat = $phone->formatE164();
            $data['phone_number'] = $internationalFormat;
        }
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
     * @throws Exception
     */
    public function updateVisibility(User $user, string $visibility): UserProfile
    {
        try {
            $profile = $this->getUserProfile($user->id);
            $id = $profile?->id;
            $this->userProfileRepository->updateVisibility($id, $visibility);

            return $profile;
        } catch (Exception $e) {
            throw new Exception('Failed to update visibility: ' . $e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function sendPhoneNumberVerificationCode(UserProfile $profile,string $phoneNumber): bool
    {
        if ($profile->phone_verified) {
            throw  new Exception('Phone number already verified');
        }
        $token = getenv("TWILIO_AUTH_TOKEN");
        $twilio_sid = getenv("TWILIO_SID");
        $twilio_verify_sid = getenv("TWILIO_VERIFY_SID");

        if (empty($token) || empty($twilio_sid) || empty($twilio_verify_sid)) {
            throw new Exception('Twilio credentials are not set in the environment variables.', 404);
        }

        try {
            // Add + . Country Code . phone number
            // Will do later

            $twilio = new Client($twilio_sid, $token);

            $twilio->verify->v2->services($twilio_verify_sid)
                ->verifications
                ->create($phoneNumber, "sms");

            return true;
        } catch (Exception $e) {
            throw new Exception('Failed to verify phone number: ' . $e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function verifyPhoneNumber(User $user,string $phoneNumber, int $code): bool
    {
        $token = getenv("TWILIO_AUTH_TOKEN");
        $twilio_sid = getenv("TWILIO_SID");
        $twilio_verify_sid = getenv("TWILIO_VERIFY_SID");

        if (empty($token) || empty($twilio_sid) || empty($twilio_verify_sid)) {
            throw new Exception('Twilio credentials are not set in the environment variables.', 404);
        }

        try {
            $twilio = new Client($twilio_sid, $token);

            $verification = $twilio->verify->v2->services($twilio_verify_sid)
                ->verificationChecks
                ->create([
                    'to' => $phoneNumber,
                    'code' => $code,
                ]);

            if ($verification->valid) {
                $profile = $this->getUserProfile($user->id);
                if (!$profile) {
                    throw new Exception('User profile not found.', 404);
                }

                $this->userProfileRepository->updatePhoneNumberStatus($profile->id, true);
                return true;
            } else {
                throw new Exception('Invalid verification code.', 400);
            }
        } catch (Exception $e) {
            throw new Exception('Failed to verify phone number: ' . $e->getMessage());
        }
    }
}
