<?php

namespace App\Livewire;

use App\Models\Friendship;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class FriendsManager extends Component
{
    public function sendFriendRequest(int $receiverId): void
    {
        $senderId = Auth::id();
        if ($senderId === $receiverId) return;

        $existing = Friendship::where(function ($q) use ($senderId, $receiverId) {
            $q->where('sender_id', $senderId)->where('receiver_id', $receiverId);
        })->orWhere(function ($q) use ($senderId, $receiverId) {
            $q->where('sender_id', $receiverId)->where('receiver_id', $senderId);
        })->first();

        if (!$existing) {
            Friendship::create([
                'sender_id' => $senderId,
                'receiver_id' => $receiverId,
                'status' => 'pending',
            ]);

            $receiver = User::find($receiverId);
            if ($receiver) {
                app(NotificationService::class)->friendRequestSent($receiver, Auth::user());
            }
        }
    }

    public function acceptFriendRequest(int $friendshipId): void
    {
        $friendship = Friendship::find($friendshipId);

        if ($friendship && $friendship->receiver_id === Auth::id()) {
            $friendship->update(['status' => 'accepted']);

            $sender = User::find($friendship->sender_id);
            if ($sender) {
                app(NotificationService::class)->friendRequestAccepted($sender, Auth::user());
            }
        }
    }

    public function rejectFriendRequest(int $friendshipId): void
    {
        $friendship = Friendship::find($friendshipId);

        if ($friendship && ($friendship->receiver_id === Auth::id() || $friendship->sender_id === Auth::id())) {
            $friendship->delete();
        }
    }

    public function unfriend(int $friendshipId): void
    {
        $userId = Auth::id();
        $friendship = Friendship::find($friendshipId);

        if ($friendship && ($friendship->sender_id === $userId || $friendship->receiver_id === $userId)) {
            $friendship->delete();
        }
    }

    public function render()
    {
        $userId = Auth::id();

        // Pending incoming requests (where I am the receiver)
        $incomingRequests = Friendship::with('sender')
            ->where('receiver_id', $userId)
            ->where('status', 'pending')
            ->get();

        // Accepted friendships
        $acceptedFriendships = Friendship::where('status', 'accepted')
            ->where(function ($q) use ($userId) {
                $q->where('sender_id', $userId)->orWhere('receiver_id', $userId);
            })->get();

        $friends = $acceptedFriendships->map(function ($f) use ($userId) {
            return [
                'friendship_id' => $f->id,
                'user' => $f->sender_id === $userId ? $f->receiver : $f->sender,
            ];
        });

        // Suggested connections (exclude friends and pending)
        $friendIds = Auth::user()->friendIds();
        $pendingIds = Friendship::where('sender_id', $userId)->where('status', 'pending')->pluck('receiver_id')->toArray();
        $pendingReceivedIds = Friendship::where('receiver_id', $userId)->where('status', 'pending')->pluck('sender_id')->toArray();
        $excludeIds = array_unique(array_merge($friendIds, $pendingIds, $pendingReceivedIds, [$userId]));

        $suggestedUsers = User::whereNotIn('id', $excludeIds)->take(10)->get();

        return view('livewire.friends-manager', [
            'incomingRequests' => $incomingRequests,
            'friends' => $friends,
            'suggestedUsers' => $suggestedUsers,
        ]);
    }
}
