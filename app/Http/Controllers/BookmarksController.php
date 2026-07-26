<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class BookmarksController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $bookmarks = Auth::user()->savedPosts()
            ->with(['user', 'media', 'group', 'parent.user'])
            ->withCount(['likes', 'comments', 'reposts'])
            ->withExists([
                'likes as is_liked_by_user' => fn ($q) => $q->where('likes.user_id', $userId),
            ])
            ->latest('saved_posts.created_at')
            ->paginate(15);

        return view('livewire.bookmarks-page', compact('bookmarks'));
    }
}
