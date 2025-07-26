<?php
// app/Services/Interfaces/AuthServiceInterface.php

namespace App\Services\Auth\Interfaces;

interface FacebookAuthServiceInterface
{
    public function redirectToFacebook(): string;
    public function handleFacebookCallback(): array;
}
