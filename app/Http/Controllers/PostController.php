<?php

namespace App\Http\Controllers;

use App\Models\Post;

class PostController extends Controller
{
    public function show(Post $post)
    {
        if ($post->group_id && $post->group->type === 'private' && !$post->group->isMember(auth()->user())) {
            abort(403);
        }

        $post->load(['user', 'media']);
        $post->loadCount(['comments']);

        return view('posts.show', [
            'post' => $post,
        ]);
    }
}
