<?php

namespace App\Services\Auth;

use App\Events\NewLogin;
use App\Repositories\UserRepository;
use App\Services\Auth\Interfaces\FacebookAuthServiceInterface;
use Laravel\Socialite\Facades\Socialite;

class FacebookAuthService implements FacebookAuthServiceInterface
{
    public function __construct(
        protected UserRepository $userRepository
    ) {}

    public function redirectToFacebook(): string
    {
        return Socialite::driver('facebook')->stateless()->scopes(['email'])->redirect()->getTargetUrl();
    }

    public function handleFacebookCallback(): array
    {
        $fbUser = Socialite::driver('facebook')->stateless()->user();

        $user = $this->userRepository->findOrCreateFacebookUser($fbUser);

        $token = $user->createToken('facebook-login')->plainTextToken;
        event(new NewLogin($user));
        return [
            'user' => $user,
            'token' => $token,
        ];
    }
}
