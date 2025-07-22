<?php
// app/Services/AuthService.php

namespace App\Services;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Interfaces\AuthServiceInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthService implements AuthServiceInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function register(array $data): User
    {
        $data['password'] = Hash::make($data['password']);

        $user = $this->userRepository->create($data);
        $token = $user->createToken('auth_token')->plainTextToken;

        return  $user->setAttribute('token', $token);
    }

    public function login(array $credentials): array
    {
        $user = $this->userRepository->findByEmail($credentials['email']);

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw new \Exception('Invalid credentials', 401);
        }

        return $this->generateAuthResponse($user);
    }

    public function logout(User $user): void
    {
        $user->tokens()->delete();
    }

    public function getUser(): ?User
    {
        return auth()->user();
    }

    public function refreshToken(User $user): array
    {
        $this->logout($user);
        return $this->generateAuthResponse($user);
    }

    private function generateAuthResponse(User $user): array
    {
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer'
        ];
    }
}
