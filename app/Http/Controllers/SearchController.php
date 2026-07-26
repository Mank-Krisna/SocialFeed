<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q', '');
        $type = $request->input('type', 'all');
        $results = collect();

        if (strlen($query) >= 2) {
            match ($type) {
                'users' => $results = User::search($query)->take(20)->get(),
                'posts' => $results = Post::search($query)->with('user')->take(20)->get(),
                'groups' => $results = Group::search($query)->take(20)->get(),
                default => $results = [
                    'users' => User::search($query)->take(5)->get(),
                    'posts' => Post::search($query)->with('user')->take(5)->get(),
                    'groups' => Group::search($query)->take(5)->get(),
                ],
            };
        }

        return view('livewire.search-center', [
            'query' => $query,
            'type' => $type,
            'results' => $results,
        ]);
    }
}
