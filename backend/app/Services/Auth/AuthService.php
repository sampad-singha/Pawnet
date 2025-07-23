<?php

namespace App\Services\Auth;

use App\Exceptions\API\Auth\InvalidCredentialsException;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Auth\Interfaces\AuthServiceInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthService implements AuthServiceInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        protected TokenNameService $tokenNameService
    ) {}

    public function register(array $data): User
    {
        $data['email'] = Str::lower(trim($data['email']));
        $data['password'] = Hash::make($data['password']);

        $user = $this->userRepository->create($data);
        $token = $user->createToken('auth_token')->plainTextToken;

        // Don’t expose entire model directly
        return $user->setAttribute('token', $token);
    }

    /**
     * @throws InvalidCredentialsException
     */
    public function login(array $credentials, string $userAgent): array
    {
        $email = Str::lower(trim($credentials['email']));
        $user = $this->userRepository->findByEmail($email);

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw new InvalidCredentialsException('Invalid credentials');
        }

        return $this->generateAuthResponse($user, $userAgent);
    }

    public function logout(User $user, ?string $userAgent = null): void
    {
        if ($userAgent) {
            $tokenName = $this->tokenNameService->generate($userAgent);
            $user->tokens()->where('name', $tokenName)->delete();
            $user->currentAccessToken()?->delete();
        } else {
            // If no userAgent provided, delete all tokens (optional fallback)
            $user->tokens()->delete();
        }
    }

    public function getUser(): ?User
    {
        return auth()->user();
    }

    public function refreshToken(User $user, string $userAgent): array
    {
        $this->logout($user); // Revoke all tokens
        return $this->generateAuthResponse($user,$userAgent);
    }

    private function generateAuthResponse(User $user, string $userAgent): array
    {
        $tokenName = $this->tokenNameService->generate($userAgent); // handles user-agent, platform, etc.
        $token = $user->createToken($tokenName)->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer'
        ];
    }

    /**
     * @throws \Exception
     */
    public function setPassword(User $user, string $newPassword): void
    {
        if ($user->set_password) {
            throw new \Exception('Password already set.');
        }

        $user->password = Hash::make($newPassword);
        $user->set_password = true;
        $user->save();
    }

    public function changePassword(User $user, string $currentPassword, string $newPassword): void
    {
        if (!Hash::check($currentPassword, $user->password)) {
            throw new \Exception('Current password is incorrect.');
        }

        $user->password = Hash::make($newPassword);
        $user->save();
    }
}
