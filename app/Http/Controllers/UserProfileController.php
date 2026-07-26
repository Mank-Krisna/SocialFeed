<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    public function show($user = null)
    {
        if ($user === null) {
            $userModel = auth()->user();
        } else {
            $userModel = User::where(function ($q) use ($user) {
                $q->where('username', $user)->orWhere('id', $user);
            })->firstOrFail();
        }

        $perPage = (int) config('feed.per_page', 10);
        $posts = $userModel->posts()
            ->withFeedRelations(auth()->id())
            ->latest()
            ->take($perPage + 1)
            ->get();

        return view('profile.show', [
            'user' => $userModel,
            'posts' => $posts,
        ]);
    }
}
