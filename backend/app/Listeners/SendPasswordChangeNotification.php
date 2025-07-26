<?php

namespace App\Listeners;

use App\Events\PasswordChange;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendPasswordChangeNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PasswordChange $event): void
    {
        $event->user->notify(new \App\Notifications\PasswordChangeNotification());
    }
}
