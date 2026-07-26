<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\Notification;
use App\Models\Post;
use App\Models\User;

class NotificationService
{
    public function postLiked(Post $post, User $sender): void
    {
        if ($post->user_id === $sender->id) return;

        Notification::create([
            'user_id' => $post->user_id,
            'sender_id' => $sender->id,
            'type' => 'like',
            'data' => [
                'message' => 'menyukai postingan Anda.',
                'link' => route('posts.show', $post->id),
            ],
        ]);
    }

    public function postCommented(Post $post, User $sender): void
    {
        if ($post->user_id === $sender->id) return;

        Notification::create([
            'user_id' => $post->user_id,
            'sender_id' => $sender->id,
            'type' => 'comment',
            'data' => [
                'message' => 'mengomentari postingan Anda.',
                'link' => route('posts.show', $post->id),
            ],
        ]);
    }

    public function commentReplied(Comment $parent, User $sender): void
    {
        if ($parent->user_id === $sender->id) return;

        Notification::create([
            'user_id' => $parent->user_id,
            'sender_id' => $sender->id,
            'type' => 'comment',
            'data' => [
                'message' => 'membalas komentar Anda.',
                'link' => route('posts.show', $parent->post_id),
            ],
        ]);
    }

    public function friendRequestSent(User $receiver, User $sender): void
    {
        Notification::create([
            'user_id' => $receiver->id,
            'sender_id' => $sender->id,
            'type' => 'friend_request',
            'data' => [
                'message' => 'mengirimkan permintaan pertemanan.',
                'link' => route('friends'),
            ],
        ]);
    }

    public function postReposted(Post $post, User $sender): void
    {
        if ($post->user_id === $sender->id) return;

        Notification::create([
            'user_id' => $post->user_id,
            'sender_id' => $sender->id,
            'type' => 'repost',
            'data' => [
                'message' => 'membagikan ulang postingan Anda.',
                'link' => route('posts.show', $post->id),
            ],
        ]);
    }

    public function friendRequestAccepted(User $sender, User $acceptor): void
    {
        Notification::create([
            'user_id' => $sender->id,
            'sender_id' => $acceptor->id,
            'type' => 'friend_accepted',
            'data' => [
                'message' => 'menerima permintaan pertemanan Anda.',
                'link' => route('profile.show', $acceptor->username ?? $acceptor->id),
            ],
        ]);
    }
}
