<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via(User $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(User $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Selamat Datang di SocialFeed!')
            ->greeting("Halo, {$notifiable->name}! 👋")
            ->line('Akun SocialFeed kamu sudah aktif dan siap digunakan.')
            ->line('Mulai bagikan momen, temukan teman baru, dan bergabunglah dengan komunitas yang kamu minati.')
            ->action('Mulai Jelajahi', url('/feed'))
            ->line('Terima kasih telah bergabung bersama SocialFeed!');
    }

    public function toArray(User $notifiable): array
    {
        return [
            'type'    => 'welcome',
            'message' => 'Selamat datang di SocialFeed! Akun kamu sudah aktif.',
        ];
    }
}
