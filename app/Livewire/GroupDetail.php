<?php

namespace App\Livewire;

use App\Models\Group;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class GroupDetail extends Component
{
    public Group $group;
    public int $perPage;

    protected $listeners = ['feed:loadMore' => 'loadMore'];

    public function mount(Group $group): void
    {
        $this->group = $group;
        $this->perPage = (int) config('feed.per_page', 10);
    }

    public function joinGroup(): void
    {
        if (!Auth::check()) return;

        if (!$this->group->isMember(Auth::user())) {
            $this->group->members()->attach(Auth::id(), ['role' => 'member']);
            $this->group->refresh();
        }
    }

    public function leaveGroup(): void
    {
        if (!Auth::check()) return;

        if ($this->group->isMember(Auth::user())) {
            $this->group->members()->detach(Auth::id());
            $this->group->refresh();
        }
    }

    #[On('post-created')]
    #[On('post-deleted')]
    public function refreshPosts(): void
    {
        $this->perPage = (int) config('feed.per_page', 10);
    }

    public function loadMore(): void
    {
        $this->perPage += (int) config('feed.per_page', 10);
    }

    public function render()
    {
        $user = Auth::user();
        $isMember = $this->group->isMember($user);
        $canViewPosts = ($this->group->type === 'public') || $isMember;

        if (!$canViewPosts) {
            return view('livewire.group-detail', [
                'isMember' => $isMember,
                'canViewPosts' => false,
                'posts' => collect(),
                'hasMore' => false,
                'members' => $this->group->members()->take(10)->get(),
            ]);
        }

        $query = Post::where('group_id', $this->group->id)
            ->withFeedRelations($user?->id)
            ->latest();

        $posts = (clone $query)->take($this->perPage + 1)->get();
        $hasMore = $posts->count() > $this->perPage;
        $posts = $hasMore ? $posts->take($this->perPage) : $posts;

        $members = $this->group->members()->take(10)->get();

        return view('livewire.group-detail', [
            'isMember' => $isMember,
            'canViewPosts' => true,
            'posts' => $posts,
            'hasMore' => $hasMore,
            'members' => $members,
        ]);
    }
}
