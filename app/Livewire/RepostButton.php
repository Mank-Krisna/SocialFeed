<?php

namespace App\Livewire;

use App\Models\Post;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class RepostButton extends Component
{
    public Post $post;
    public int $repostsCount = 0;

    public function mount(Post $post): void
    {
        $this->post = $post;
        $this->repostsCount = $post->reposts_count ?? $post->reposts()->count();
    }

    public function repost(): void
    {
        if (!Auth::check()) return;

        Post::create([
            'user_id' => Auth::id(),
            'body' => '',
            'parent_id' => $this->post->id,
        ]);

        $this->repostsCount++;

        app(NotificationService::class)->postReposted($this->post, Auth::user());

        $this->dispatch('post-created');
        $this->dispatch('notify', message: 'Postingan dibagikan ulang', type: 'success');
    }

    public function render()
    {
        return view('livewire.repost-button');
    }
}
