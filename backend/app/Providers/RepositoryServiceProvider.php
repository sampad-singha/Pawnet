<?php
// app/Providers/RepositoryServiceProvider.php

namespace App\Providers;

use App\Repositories\FriendRepository;
use App\Repositories\Interfaces\FriendRepositoryInterface;
use App\Repositories\Interfaces\UserProfileRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\UserProfileRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class
        );

        // Bind the FriendRepositoryInterface to FriendRepository
        $this->app->bind(
            FriendRepositoryInterface::class,
            FriendRepository::class
        );

        $this->app->bind(
            UserProfileRepositoryInterface::class,
            UserProfileRepository::class
        );
    }
}
