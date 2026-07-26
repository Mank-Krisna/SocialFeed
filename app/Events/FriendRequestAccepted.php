<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FriendRequestAccepted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly User $acceptor,
        public readonly User $requester,
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->requester->id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'friend.request.accepted';
    }

    public function broadcastWith(): array
    {
        return [
            'acceptor_id'     => $this->acceptor->id,
            'acceptor_name'   => $this->acceptor->name,
            'acceptor_avatar' => $this->acceptor->avatar_url,
            'message'         => "{$this->acceptor->name} menerima permintaan pertemananmu.",
        ];
    }
}
