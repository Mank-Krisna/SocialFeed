<?php

namespace App\Livewire;

use App\Models\Post;
use App\Models\SavedPost;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class BookmarkButton extends Component
{
    public Post $post;
    public bool $isSaved = false;

    public function mount(Post $post): void
    {
        $this->post = $post;
        $this->isSaved = $post->is_saved_by_user ?? SavedPost::where('user_id', Auth::id())
            ->where('post_id', $post->id)
            ->exists();
    }

    public function toggleBookmark(): void
    {
        if (!Auth::check()) return;

        $existing = SavedPost::where('user_id', Auth::id())
            ->where('post_id', $this->post->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $this->isSaved = false;
            $this->dispatch('notify', message: 'Postingan dihapus dari simpanan', type: 'success');
        } else {
            SavedPost::create([
                'user_id' => Auth::id(),
                'post_id' => $this->post->id,
            ]);
            $this->isSaved = true;
            $this->dispatch('notify', message: 'Postingan disimpan', type: 'success');
        }
    }

    public function render()
    {
        return view('livewire.bookmark-button');
    }
}
