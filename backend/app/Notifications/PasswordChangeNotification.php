<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordChangeNotification extends Notification
{
    use Queueable;
    public function __construct()
    {
        //
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Password Changed Successfully')
            ->line('Your password has been changed successfully.')
            ->line('If you did not initiate this change, please change your password and log out of all devices.')
            ->action('Change Password', url('/change-password'))
            ->line('Thank you for using our application!');
    }
}
