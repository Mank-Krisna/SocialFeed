<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FriendRequestSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly User $sender,
        public readonly User $receiver,
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->receiver->id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'friend.request.sent';
    }

    public function broadcastWith(): array
    {
        return [
            'sender_id'     => $this->sender->id,
            'sender_name'   => $this->sender->name,
            'sender_avatar' => $this->sender->avatar_url,
            'message'       => "{$this->sender->name} mengirimkan permintaan pertemanan.",
        ];
    }
}
