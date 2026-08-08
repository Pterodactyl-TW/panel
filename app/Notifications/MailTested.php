<?php

namespace Pterodactyl\Notifications;

use Pterodactyl\Models\User;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class MailTested extends Notification
{
    public function __construct(private User $user)
    {
    }

    public function via(): array
    {
        return ['mail'];
    }

    public function toMail(): MailMessage
    {
        return (new MailMessage())
            ->subject('Pterodactyl 測試郵件')
            ->greeting('您好，' . $this->user->name . '！')
            ->line('這是一封 Pterodactyl 郵件系統的測試信，一切設定完成！');
    }
}
