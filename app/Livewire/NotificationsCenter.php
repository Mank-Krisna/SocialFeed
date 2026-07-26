<?php

namespace App\Livewire;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NotificationsCenter extends Component
{
    public function markAsRead(int $notificationId): void
    {
        $notif = Notification::find($notificationId);

        if ($notif && $notif->user_id === Auth::id()) {
            $notif->update(['read_at' => now()]);
        }
    }

    public function markAllAsRead(): void
    {
        Notification::where('user_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    public function render()
    {
        $notifications = Auth::user()->notifications()->with('sender')->take(30)->get();

        return view('livewire.notifications-center', [
            'notifications' => $notifications,
        ]);
    }
}
