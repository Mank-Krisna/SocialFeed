<?php

namespace App\Livewire;

use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Messenger extends Component
{
    public $conversations;
    public $messages = [];
    public $activeConversationId = null;
    public string $body = '';
    public string $searchQuery = '';

    public function mount(): void
    {
        $this->loadConversations();
    }

    public function loadConversations(): void
    {
        $user = Auth::user();
        $all = $user->conversations()
            ->with(['messages' => fn ($q) => $q->latest()->take(1)])
            ->get();

        $all->each(function ($c) use ($user) {
            $c->unread = $c->unreadCountFor($user);
        });

        $this->conversations = $all->sortByDesc(function ($c) {
            $last = $c->messages->first();
            return $last?->created_at ?? $c->created_at;
        })->values();
    }

    public function openConversation(int $conversationId): void
    {
        $this->activeConversationId = $conversationId;
        $this->loadMessages();
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
        $this->messages = [];
        $this->loadConversations();

        $this->dispatch('notify', message: 'Percakapan baru dimulai', type: 'success');
    }

    public function loadMessages(): void
    {
        if (!$this->activeConversationId) return;

        $this->messages = Message::where('conversation_id', $this->activeConversationId)
            ->with('user')
            ->oldest()
            ->get();
    }

    public function markAsRead(): void
    {
        if (!$this->activeConversationId) return;

        Auth::user()->conversations()->updateExistingPivot($this->activeConversationId, [
            'last_read_at' => now(),
        ]);

        $this->loadConversations();
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
        $this->loadMessages();
        $this->loadConversations();
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
