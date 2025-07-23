<?php

namespace App\Services;

use App\Exceptions\API\Auth\InvalidCredentialsException;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Interfaces\AuthServiceInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AuthService implements AuthServiceInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function register(array $data): User
    {
        $data['email'] = Str::lower(trim($data['email']));
        $data['password'] = Hash::make($data['password']);

        $user = $this->userRepository->create($data);
        $token = $user->createToken('auth_token')->plainTextToken;

        // Don’t expose entire model directly
        return $user->setAttribute('token', $token)->makeHidden(['password', 'email_verified_at']);
    }

    /**
     * @throws InvalidCredentialsException
     */
    public function login(array $credentials): array
    {
        $email = Str::lower(trim($credentials['email']));
        $user = $this->userRepository->findByEmail($email);

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw new InvalidCredentialsException('Invalid credentials');
        }

        return $this->generateAuthResponse($user);
    }

    public function logout(User $user): void
    {
        // You can log this action for auditing
        $user->tokens()->delete();
    }

    public function getUser(): ?User
    {
        return auth()->user()?->makeHidden(['password', 'email_verified_at']);
    }

    public function refreshToken(User $user): array
    {
        $this->logout($user); // Revoke all tokens
        return $this->generateAuthResponse($user);
    }

    private function generateAuthResponse(User $user): array
    {
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user->makeHidden(['password', 'email_verified_at']),
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
