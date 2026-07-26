<?php

namespace App\Livewire;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class Feed extends Component
{
    public string $filter = 'all';
    public int $perPage;

    public function mount(): void
    {
        $this->perPage = (int) config('feed.per_page', 10);
    }

    #[On('post-created')]
    #[On('post-deleted')]
    public function refreshFeed(): void
    {
        $this->perPage = (int) config('feed.per_page', 10);
    }

    public function loadMore(): void
    {
        $this->perPage += (int) config('feed.per_page', 10);
    }

    public function setFilter(string $filter): void
    {
        $this->filter = $filter;
        $this->perPage = (int) config('feed.per_page', 10);
    }

    public function render()
    {
        $user = Auth::user();
        $myGroupIds = $user ? $user->groups()->pluck('groups.id')->toArray() : [];

        $query = Post::withFeedRelations($user?->id)->latest();

        $query->where(function ($q) use ($myGroupIds) {
            $q->whereNull('group_id')
              ->orWhereHas('group', function ($gQ) use ($myGroupIds) {
                  $gQ->where('type', 'public')
                     ->orWhereIn('id', $myGroupIds);
              });
        });

        if ($this->filter === 'friends' && $user) {
            $friendIds = $user->friendIds();
            $friendIds[] = $user->id;
            $query->whereIn('user_id', $friendIds);
        }

        $posts = (clone $query)->take($this->perPage + 1)->get();
        $hasMore = $posts->count() > $this->perPage;
        $posts = $hasMore ? $posts->take($this->perPage) : $posts;

        return view('livewire.feed', [
            'posts' => $posts,
            'hasMore' => $hasMore,
            'filter' => $this->filter,
        ]);
    }
}
