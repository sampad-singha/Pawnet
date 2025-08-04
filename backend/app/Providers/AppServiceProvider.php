<?php

namespace App\Providers;

use App\Policies\FriendPolicy;
use App\Policies\UserProfilePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('sendFriendRequest', [FriendPolicy::class, 'sendFriendRequest']);
        Gate::define('cancelFriendRequest', [FriendPolicy::class, 'cancelFriendRequest']);
        Gate::define('acceptFriendRequest', [FriendPolicy::class, 'acceptFriendRequest']);
        Gate::define('rejectFriendRequest', [FriendPolicy::class, 'rejectFriendRequest']);
        Gate::define('unFriend', [FriendPolicy::class, 'unFriend']);

        Gate::define('viewOwnProfile', [UserProfilePolicy::class, 'viewOwnProfile']);
        Gate::define('viewUserProfile', [UserProfilePolicy::class, 'viewUserProfile']);
        Gate::define('createUserProfile', [UserProfilePolicy::class, 'createUserProfile']);
        Gate::define('updateUserProfile', [UserProfilePolicy::class, 'updateUserProfile']);
        Gate::define('changeVisibility', [UserProfilePolicy::class, 'changeVisibility']);
    }
}
