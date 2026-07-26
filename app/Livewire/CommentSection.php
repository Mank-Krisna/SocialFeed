<?php

namespace App\Livewire;

use App\Models\Comment;
use App\Models\Post;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CommentSection extends Component
{
    public Post $post;
    public string $body = '';
    public ?int $replyToCommentId = null;
    public string $replyBody = '';

    public function submitComment(): void
    {
        $this->validate([
            'body' => 'required|string|min:1|max:300',
        ]);

        Comment::create([
            'user_id' => Auth::id(),
            'post_id' => $this->post->id,
            'parent_id' => null,
            'body' => trim($this->body),
        ]);

        app(NotificationService::class)->postCommented($this->post, Auth::user());

        $this->reset('body');
        $this->dispatch('comment-added');
    }

    public function toggleReply(?int $commentId): void
    {
        if ($this->replyToCommentId === $commentId) {
            $this->replyToCommentId = null;
            $this->replyBody = '';
        } else {
            $this->replyToCommentId = $commentId;
            $this->replyBody = '';
        }
    }

    public function submitReply(int $parentId): void
    {
        $this->validate([
            'replyBody' => 'required|string|min:1|max:300',
        ]);

        Comment::create([
            'user_id' => Auth::id(),
            'post_id' => $this->post->id,
            'parent_id' => $parentId,
            'body' => trim($this->replyBody),
        ]);

        $parentComment = Comment::find($parentId);
        if ($parentComment) {
            app(NotificationService::class)->commentReplied($parentComment, Auth::user());
        }

        $this->replyToCommentId = null;
        $this->replyBody = '';
        $this->dispatch('comment-added');
    }

    public function render()
    {
        $comments = $this->post->topLevelComments()->with(['user', 'replies.user'])->get();

        return view('livewire.comment-section', [
            'comments' => $comments,
        ]);
    }
}
