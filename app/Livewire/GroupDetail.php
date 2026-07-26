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

    public function mount(Group $group): void
    {
        $this->group = $group;
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
        // Re-render
    }

    public function render()
    {
        $user = Auth::user();
        $isMember = $this->group->isMember($user);
        $canViewPosts = ($this->group->type === 'public') || $isMember;

        $posts = $canViewPosts
            ? Post::where('group_id', $this->group->id)->withFeedRelations($user?->id)->latest()->get()
            : collect();

        $members = $this->group->members()->take(10)->get();

        return view('livewire.group-detail', [
            'isMember' => $isMember,
            'canViewPosts' => $canViewPosts,
            'posts' => $posts,
            'members' => $members,
        ]);
    }
}
