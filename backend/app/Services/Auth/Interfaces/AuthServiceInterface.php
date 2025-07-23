<?php
// app/Services/Interfaces/AuthServiceInterface.php

namespace App\Services\Auth\Interfaces;

use App\Models\User;

interface AuthServiceInterface
{
    public function register(array $data): User;
    public function login(array $credentials, string $userAgent): array;
    public function logout(User $user, ?string $userAgent = null): void;
    public function getUser(): ?User;
    public function refreshToken(User $user, string $userAgent): array;
    public function setPassword(User $user, string $newPassword): void;
    public function changePassword(User $user, string $currentPassword, string $newPassword): void;
}
