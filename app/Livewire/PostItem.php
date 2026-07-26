<?php

namespace App\Livewire;

use App\Models\Like;
use App\Models\Post;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PostItem extends Component
{
    public Post $post;
    public bool $isLiked = false;
    public int $likesCount = 0;
    public int $commentsCount = 0;
    public int $repostsCount = 0;
    public bool $showComments = false;

    public function mount(Post $post): void
    {
        $this->post = $post;
        $this->isLiked = $post->is_liked_by_user ?? $post->isLikedBy(Auth::user());
        $this->likesCount = $post->likes_count ?? $post->likes()->count();
        $this->commentsCount = $post->comments_count ?? $post->comments()->count();
        $this->repostsCount = $post->reposts_count ?? $post->reposts()->count();
    }

    public function toggleLike(): void
    {
        $userId = Auth::id();

        $like = Like::where('user_id', $userId)->where('post_id', $this->post->id)->first();

        if ($like) {
            $like->delete();
            $this->isLiked = false;
            $this->likesCount = max(0, $this->likesCount - 1);
        } else {
            Like::create([
                'user_id' => $userId,
                'post_id' => $this->post->id,
            ]);
            $this->isLiked = true;
            $this->likesCount++;

            app(NotificationService::class)->postLiked($this->post, Auth::user());
        }
    }

    public function toggleComments(): void
    {
        $this->showComments = !$this->showComments;
    }

    public function deletePost(): void
    {
        if ($this->post->user_id === Auth::id()) {
            $this->post->delete();
            $this->dispatch('post-deleted');
            $this->dispatch('notify', message: 'Postingan berhasil dihapus', type: 'success');
        }
    }

    #[\Livewire\Attributes\On('comment-added')]
    public function updateCommentsCount(): void
    {
        $this->commentsCount = $this->post->comments()->count();
    }

    public function render()
    {
        return view('livewire.post-item');
    }
}
