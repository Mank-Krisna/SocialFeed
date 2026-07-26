<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WeeklyDigestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly int $newPostsCount,
        private readonly int $newFriendsCount,
        private readonly int $newNotificationsCount,
    ) {}

    public function via(User $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(User $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Ringkasan Mingguan SocialFeed Kamu 📊')
            ->greeting("Hai, {$notifiable->name}!")
            ->line("Berikut adalah ringkasan aktivitas minggu ini di SocialFeed:")
            ->line("📝 **{$this->newPostsCount}** postingan baru di feed kamu")
            ->line("👥 **{$this->newFriendsCount}** teman baru")
            ->line("🔔 **{$this->newNotificationsCount}** notifikasi yang belum dibaca")
            ->action('Lihat Feed Kamu', url('/feed'))
            ->line('Tetap terhubung bersama komunitas SocialFeed!');
    }

    public function toArray(User $notifiable): array
    {
        return [
            'type'                   => 'weekly_digest',
            'new_posts_count'        => $this->newPostsCount,
            'new_friends_count'      => $this->newFriendsCount,
            'new_notifications_count'=> $this->newNotificationsCount,
        ];
    }
}
