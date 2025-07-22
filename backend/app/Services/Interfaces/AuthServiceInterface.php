<?php
// app/Services/Interfaces/AuthServiceInterface.php

namespace App\Services\Interfaces;

use App\Models\User;

interface AuthServiceInterface
{
    public function register(array $data): User;
    public function login(array $credentials): array;
    public function logout(User $user): void;
    public function getUser(): ?User;
    public function refreshToken(User $user): array;
}
