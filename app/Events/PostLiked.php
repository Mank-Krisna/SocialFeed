<?php

namespace App\Events;

use App\Models\Post;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PostLiked implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly Post $post,
        public readonly User $liker,
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->post->user_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'post.liked';
    }

    public function broadcastWith(): array
    {
        return [
            'post_id'    => $this->post->id,
            'liker_name' => $this->liker->name,
            'liker_avatar' => $this->liker->avatar_url,
            'message'    => "{$this->liker->name} menyukai postinganmu.",
        ];
    }
}
