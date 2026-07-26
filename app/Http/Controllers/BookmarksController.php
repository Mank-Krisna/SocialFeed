<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class BookmarksController extends Controller
{
    public function index()
    {
        $bookmarks = Auth::user()->savedPosts()
            ->with(['user', 'media', 'group'])
            ->latest('saved_posts.created_at')
            ->paginate(15);

        return view('livewire.bookmarks-page', compact('bookmarks'));
    }
}
