<?php
// app/Services/Interfaces/AuthServiceInterface.php

namespace App\Services\Auth\Interfaces;

interface GoogleAuthServiceInterface
{
    public function redirectToGoogle(): string;

    public function handleGoogleCallback(): array;
}
