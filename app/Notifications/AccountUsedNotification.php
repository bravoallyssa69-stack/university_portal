<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountUsedNotification extends Notification
{
    use Queueable;

    public function __construct() {}

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Security Alert: Login Detected on Your Account')
                    ->greeting('Hello ' . $notifiable->name . '!')
                    ->line('We detected a new login activity on your University Portal account.')
                    ->line('Account Email: ' . $notifiable->email)
                    ->line('Date & Time of Login: ' . now()->format('F j, Y - g:i A T'))
                    ->action('Access University Portal', url('/login'))
                    ->line('If you performed this login, you can safely ignore this email.')
                    ->line('If you did NOT authorize this login, please change your password immediately or contact your portal administrator.')
                    ->salutation('Best regards, ' . PHP_EOL . 'University Portal Security Team');
    }
}