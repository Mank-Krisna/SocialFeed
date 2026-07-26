<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class BookmarksController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $bookmarks = Post::query()
            ->whereHas('savedByUsers', fn ($q) => $q->where('saved_posts.user_id', $userId))
            ->with(['user', 'media', 'group', 'parent.user'])
            ->withCount(['likes', 'comments', 'reposts'])
            ->withExists([
                'likes as is_liked_by_user' => fn ($q) => $q->where('likes.user_id', $userId),
                'savedByUsers as is_saved_by_user' => fn ($q) => $q->where('saved_posts.user_id', $userId),
            ])
            ->latest()
            ->paginate(15);

        return view('livewire.bookmarks-page', compact('bookmarks'));
    }
}
