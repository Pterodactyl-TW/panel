<?php

namespace Pterodactyl\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SendPasswordReset extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public string $token)
    {
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(mixed $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('重設密碼')
            ->line('你收到這封電子郵件是因為我們收到了你帳號的密碼重設請求。')
            ->action('重設密碼', url('/auth/password/reset/' . $this->token . '?email=' . urlencode($notifiable->email)))
            ->line('若你並未提出密碼重設請求，則無需採取任何進一步的動作。');
    }
}
