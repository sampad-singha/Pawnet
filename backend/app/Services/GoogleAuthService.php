<?php
namespace App\Services;

use App\Services\Interfaces\GoogleAuthServiceInterface;
use Laravel\Socialite\Facades\Socialite;
use App\Repositories\UserRepository;

class GoogleAuthService implements GoogleAuthServiceInterface
{
    public function __construct(
        protected UserRepository $userRepository
    ) {}

    public function redirectToGoogle(): string
    {
        return Socialite::driver('google')->stateless()->redirect()->getTargetUrl();
    }

    public function handleGoogleCallback(): array
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $user = $this->userRepository->findOrCreateGoogleUser($googleUser);

        $token = $user->createToken('google-login')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }
}
