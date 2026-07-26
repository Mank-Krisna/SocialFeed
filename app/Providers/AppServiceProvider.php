<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\Friendship;
use App\Models\Group;
use App\Models\Hashtag;
use App\Models\Post;
use App\Models\User;
use App\Policies\CommentPolicy;
use App\Policies\GroupPolicy;
use App\Policies\PostPolicy;
use App\Services\UsernameGenerator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Register authorization policies
        Gate::policy(Post::class, PostPolicy::class);
        Gate::policy(Comment::class, CommentPolicy::class);
        Gate::policy(Group::class, GroupPolicy::class);

        View::composer('components.right-sidebar', function ($view) {
            $user = Auth::user();

            if (!$user) {
                $view->with('suggestedUsers', collect());
                return;
            }

            $friendIds = $user->friendIds();
            $pendingSentIds = Friendship::where('sender_id', $user->id)
                ->where('status', 'pending')->pluck('receiver_id')->toArray();
            $pendingReceivedIds = Friendship::where('receiver_id', $user->id)
                ->where('status', 'pending')->pluck('sender_id')->toArray();
            $excludeIds = array_unique(array_merge($friendIds, $pendingSentIds, $pendingReceivedIds, [$user->id]));

            $suggestedUsers = User::whereNotIn('id', $excludeIds)
                ->inRandomOrder()
                ->take(3)
                ->get()
                ->map(function ($suggested) use ($user) {
                    $gen = app(UsernameGenerator::class);
                    return [
                        'id' => $suggested->id,
                        'name' => $suggested->name,
                        'username' => $suggested->username,
                        'username_display' => e($suggested->username_display),
                        'avatar_url' => $suggested->avatar_url,
                        'initial' => strtoupper(substr($suggested->name, 0, 1)),
                    ];
                });

            $view->with('suggestedUsers', $suggestedUsers);

            $trendingHashtags = Hashtag::withCount('posts')
                ->orderByDesc('posts_count')
                ->take(5)
                ->get();

            $view->with('trendingHashtags', $trendingHashtags);
        });
    }
}
