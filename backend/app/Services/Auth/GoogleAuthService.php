<?php
namespace App\Services\Auth;

use App\Events\NewLogin;
use App\Repositories\UserRepository;
use App\Services\Auth\Interfaces\GoogleAuthServiceInterface;
use Laravel\Socialite\Facades\Socialite;

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
        event(new NewLogin($user));
        return [
            'user' => $user,
            'token' => $token,
        ];
    }
}
