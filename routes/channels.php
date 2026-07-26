<?php

use App\Models\Conversation;
use Illuminate\Support\Facades\Broadcast;

// Private notifications channel — only the authenticated user can listen to their own channel
Broadcast::channel('user.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Messenger: only participants can listen to a conversation
Broadcast::channel('conversation.{id}', function ($user, $id) {
    $conv = Conversation::find($id);
    return $conv && $conv->users()->where('user_id', $user->id)->exists();
});
