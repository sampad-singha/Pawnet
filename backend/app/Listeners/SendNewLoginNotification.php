<?php

namespace App\Listeners;

use App\Events\NewLogin;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendNewLoginNotification
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
    public function handle(NewLogin $event): void
    {
        $event->user->notify(new \App\Notifications\NewLoginNotification());
    }
}
