<?php
// app/Providers/ServiceServiceProvider.php

namespace App\Providers;

use App\Services\Auth\AuthService;
use App\Services\Auth\GoogleAuthService;
use App\Services\Auth\Interfaces\AuthServiceInterface;
use App\Services\Auth\Interfaces\GoogleAuthServiceInterface;
use App\Services\User\FriendService;
use App\Services\User\Interfaces\FriendServiceInterface;
use App\Services\User\Interfaces\UserProfileServiceInterface;
use App\Services\User\UserProfileService;
use Illuminate\Support\ServiceProvider;

class ServiceServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            AuthServiceInterface::class,
            AuthService::class
        );
        $this->app->bind(
            GoogleAuthServiceInterface::class,
            GoogleAuthService::class
        );
        // Bind the FriendServiceInterface to FriendService
        $this->app->bind(
            FriendServiceInterface::class,
            FriendService::class
        );
        $this->app->bind(
            UserProfileServiceInterface::class,
            UserProfileService::class
        );

    }
}
