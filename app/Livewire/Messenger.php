<?php

namespace App\Livewire;

use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Messenger extends Component
{
    public ?int $activeConversationId = null;
    public string $body = '';
    public string $searchQuery = '';

    #[Computed]
    public function conversations()
    {
        $user = Auth::user();
        if (!$user) {
            return collect();
        }

        $all = $user->conversations()
            ->with(['messages' => fn ($q) => $q->latest()->take(1)])
            ->withCount(['messages as unread_count' => function ($q) use ($user) {
                $q->where('user_id', '!=', $user->id)
                  ->where(function ($q2) {
                      $q2->whereNull('conversation_user.last_read_at')
                         ->orWhereColumn('messages.created_at', '>', 'conversation_user.last_read_at');
                  });
            }])
            ->get();

        return $all->sortByDesc(function ($c) {
            $last = $c->messages->first();
            return $last?->created_at ?? $c->created_at;
        })->values();
    }

    #[Computed]
    public function messages()
    {
        if (!$this->activeConversationId) {
            return collect();
        }

        return Message::where('conversation_id', $this->activeConversationId)
            ->with('user')
            ->oldest()
            ->get();
    }

    public function openConversation(int $conversationId): void
    {
        $this->activeConversationId = $conversationId;
        $this->markAsRead();
    }

    public function openOrCreateConversation(int $userId): void
    {
        if ($userId === Auth::id()) return;

        $existing = Conversation::whereHas('users', fn ($q) => $q->where('user_id', Auth::id()))
            ->whereHas('users', fn ($q) => $q->where('user_id', $userId))
            ->where('type', 'private')
            ->first();

        if ($existing) {
            $this->openConversation($existing->id);
            return;
        }

        $conv = Conversation::create(['type' => 'private']);
        $conv->users()->attach([Auth::id(), $userId]);

        $this->activeConversationId = $conv->id;
        $this->dispatch('notify', message: 'Percakapan baru dimulai', type: 'success');
    }

    public function markAsRead(): void
    {
        if (!$this->activeConversationId) return;

        Auth::user()->conversations()->updateExistingPivot($this->activeConversationId, [
            'last_read_at' => now(),
        ]);

        $this->dispatch('message-read');
    }

    public function sendMessage(): void
    {
        $this->validate(['body' => 'required|string|max:2000']);

        if (!$this->activeConversationId) return;

        $conv = Conversation::find($this->activeConversationId);
        if (!$conv || !$conv->users()->where('user_id', Auth::id())->exists()) return;

        $msg = Message::create([
            'conversation_id' => $this->activeConversationId,
            'user_id' => Auth::id(),
            'body' => trim($this->body),
        ]);

        try {
            broadcast(new MessageSent($msg, $conv, Auth::user()))->toOthers();
        } catch (\Throwable $e) {
            // Pusher not configured — rely on wire:poll fallback
        }

        $otherUser = $conv->otherUser(Auth::user());
        if ($otherUser) {
            Notification::create([
                'user_id' => $otherUser->id,
                'sender_id' => Auth::id(),
                'type' => 'message',
                'data' => [
                    'message' => 'mengirim pesan baru.',
                    'link' => route('messages'),
                ],
            ]);
        }

        $this->body = '';
        $this->markAsRead();
    }

    public function startNewFromSearch(): void
    {
        $q = trim($this->searchQuery);
        if (strlen($q) < 1) return;

        $user = User::where('username', $q)
            ->orWhere('name', 'like', "%{$q}%")
            ->where('id', '!=', Auth::id())
            ->first();

        if ($user) {
            $this->searchQuery = '';
            $this->openOrCreateConversation($user->id);
        }
    }

    public function render()
    {
        $searchResults = collect();
        if (strlen(trim($this->searchQuery)) >= 1) {
            $searchResults = User::where('id', '!=', Auth::id())
                ->where(function ($q) {
                    $q->where('name', 'like', "%{$this->searchQuery}%")
                      ->orWhere('username', 'like', "%{$this->searchQuery}%");
                })->take(10)->get();
        }

        return view('livewire.messenger', [
            'searchResults' => $searchResults,
        ]);
    }
}
