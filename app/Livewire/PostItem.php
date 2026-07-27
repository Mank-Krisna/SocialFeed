<?php

namespace App\Livewire;

use App\Models\Reaction;
use App\Models\Post;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class PostItem extends Component
{
    public Post $post;
    public bool $showReactionPicker = false;
    public int $commentsCount = 0;
    public int $repostsCount = 0;
    public bool $showComments = false;

    public function mount(Post $post): void
    {
        $this->post = $post;
        $this->commentsCount = $post->comments_count ?? $post->comments()->count();
        $this->repostsCount = $post->reposts_count ?? $post->reposts()->count();
    }

    public function react(string $type): void
    {
        if (!in_array($type, array_keys(Reaction::TYPES))) {
            return;
        }

        $existing = $this->post->reactions()
            ->where('user_id', auth()->id())
            ->first();

        if ($existing) {
            if ($existing->type === $type) {
                $existing->delete();
                $this->dispatch('notify', message: 'Reaksi dihapus', type: 'success');
            } else {
                $existing->update(['type' => $type]);
                $this->dispatch('notify', message: 'Reaksi diperbarui', type: 'success');
            }
        } else {
            $this->post->reactions()->create([
                'user_id' => auth()->id(),
                'type' => $type,
            ]);

            if ($this->post->user_id !== auth()->id()) {
                app(NotificationService::class)->postReacted($this->post, $type);
            }

            $this->dispatch('notify', message: 'Reaksi ditambahkan', type: 'success');
        }

        $this->showReactionPicker = false;
        $this->dispatch('post-reacted');
    }

    public function getReactionCountsProperty()
    {
        return $this->post->reaction_counts;
    }

    public function getUserReactionProperty()
    {
        return $this->post->user_reaction;
    }

    public function toggleComments(): void
    {
        $this->showComments = !$this->showComments;
    }

    public function deletePost(): void
    {
        Gate::authorize('delete', $this->post);

        $this->post->delete();
        $this->dispatch('post-deleted');
        $this->dispatch('notify', message: 'Postingan berhasil dihapus', type: 'success');
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
