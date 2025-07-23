<?php
// app/Providers/ServiceServiceProvider.php

namespace App\Providers;

use App\Services\AuthService;
use App\Services\GoogleAuthService;
use App\Services\Interfaces\AuthServiceInterface;
use App\Services\Interfaces\GoogleAuthServiceInterface;
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

    }
}
