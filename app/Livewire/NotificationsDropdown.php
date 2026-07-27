<?php

namespace App\Livewire;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NotificationsDropdown extends Component
{
    public bool $open = false;

    protected $listeners = ['notification-created' => '$refresh'];

    public function toggle(): void
    {
        $this->open = !$this->open;

        if ($this->open) {
            $this->markVisibleAsRead();
        }
    }

    public function close(): void
    {
        $this->open = false;
    }

    public function markAsRead(int $id): void
    {
        $notif = Notification::find($id);
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

    private function markVisibleAsRead(): void
    {
        Notification::where('user_id', Auth::id())
            ->whereNull('read_at')
            ->take(10)
            ->update(['read_at' => now()]);
    }

    public function getNotificationsProperty()
    {
        return Auth::user()->notifications()
            ->with('sender')
            ->take(15)
            ->get();
    }

    public function getUnreadCountProperty(): int
    {
        return Auth::user()->unreadNotificationsCount();
    }

    public function render()
    {
        return view('livewire.notifications-dropdown');
    }
}
