<?php

namespace App\Http\Controllers;

use App\Models\Hashtag;
use App\Models\Post;

class HashtagController extends Controller
{
    public function index()
    {
        $trending = Hashtag::withCount('posts')
            ->orderByDesc('posts_count')
            ->take(20)
            ->get();

        return view('livewire.hashtag-explore', compact('trending'));
    }

    public function show(string $tag)
    {
        $hashtag = Hashtag::where('tag', $tag)->firstOrFail();
        $posts = Post::whereHas('hashtags', fn ($q) => $q->where('tag', $tag))
            ->withFeedRelations(auth()->id())
            ->latest()
            ->paginate(15);

        return view('livewire.hashtag-detail', [
            'hashtag' => $hashtag,
            'posts' => $posts,
        ]);
    }
}
