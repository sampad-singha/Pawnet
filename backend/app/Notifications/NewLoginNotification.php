<?php

namespace App\Notifications;

use App\Services\Auth\LocationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Jenssegers\Agent\Agent;

class NewLoginNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $rawUa = request()->header('User-Agent') ?? '';
        $agent = new Agent();
        $agent->setUserAgent($rawUa);

        $browser = $agent->browser() ?: 'Unknown';
        $platform = $agent->platform() ?: 'Unknown';
        $deviceType = $agent->device() ?: ($agent->isDesktop() ? 'Desktop' : 'Unknown');

        // Replace with request()->ip() in production
//        $ip = '101.33.22.26';
        $ip = request()->ip();

        // Resolve location
        $locationService = app(LocationService::class);
        $locationData = $locationService->getLocation($ip);

        $location = $locationData['city'] && $locationData['region'] && $locationData['country']
            ? "{$locationData['city']}, {$locationData['region']}, {$locationData['country']}"
            : 'Unknown Location';

        $time = now()->toDayDateTimeString();

        return (new MailMessage)
            ->subject('New Login Detected')
            ->greeting('Hello!')
            ->line('A new login was detected with these details:')
            ->line("**Browser:** {$browser}")
            ->line("**Platform:** {$platform}")
            ->line("**Device:** {$deviceType}")
            ->line("**Location:** {$location}")
            ->line("**Time:** {$time}")
            ->action('Secure Your Account', url('/change-password'))
            ->line('If this wasn’t you, please secure your account immediately.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
