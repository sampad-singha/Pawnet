<?php
// app/Services/Interfaces/AuthServiceInterface.php

namespace App\Services\Interfaces;

interface GoogleAuthServiceInterface
{
    public function redirectToGoogle(): string;

    public function handleGoogleCallback(): array;
}
