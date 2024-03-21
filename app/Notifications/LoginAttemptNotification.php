<?php

namespace App\Notifications;

use App\Models\PasswordReset;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class LoginAttemptNotification extends Notification
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
    public function toMail(object $notifiable): MailMessage|null
    {
        // TODO: is using cache for this good practice => for later

        $mail = ($notifiable->routes['mail']);

        $token = Str::random(50);
        $reset = Str::random(32);
        $passReset = PasswordReset::create(
            [
                'email' => $mail,
                'token' => $token,
                'reset_token' => $reset
            ]
        );

        return (new MailMessage)
            ->line('Someone trying to login with your email, if this is not you, you can simply change your password')
            ->action('Change password', url('/reset', ['token' => $passReset->token]))
            ->line('Thank you for using our application!');
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
