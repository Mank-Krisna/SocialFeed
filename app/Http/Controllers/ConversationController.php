<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConversationController extends Controller
{
    public function index()
    {
        $conversations = Conversation::where('sender_id', Auth::id())
            ->orWhere('receiver_id', Auth::id())
            ->with(['sender', 'receiver', 'lastMessage'])
            ->latest('updated_at')
            ->get();

        return view('livewire.messenger', compact('conversations'));
    }

    public function show(Conversation $conversation)
    {
        if ($conversation->sender_id !== Auth::id() && $conversation->receiver_id !== Auth::id()) {
            abort(403);
        }

        $conversation->load(['sender', 'receiver', 'messages.sender']);
        $conversation->where('sender_id', Auth::id())->where('receiver_id', Auth::id())->update(['is_read' => true]);

        return view('livewire.messenger', [
            'conversations' => Conversation::where('sender_id', Auth::id())
                ->orWhere('receiver_id', Auth::id())
                ->with(['sender', 'receiver', 'lastMessage'])
                ->latest('updated_at')
                ->get(),
            'activeConversation' => $conversation,
        ]);
    }

    public function send(Request $request, Conversation $conversation)
    {
        if ($conversation->sender_id !== Auth::id() && $conversation->receiver_id !== Auth::id()) {
            abort(403);
        }

        $request->validate(['body' => 'required|string|max:1000']);

        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => Auth::id(),
            'body' => $request->body,
        ]);

        $conversation->update(['last_message_at' => now()]);

        return back();
    }
}
