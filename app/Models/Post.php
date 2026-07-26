<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'group_id',
        'parent_id',
        'body',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function media(): HasMany
    {
        return $this->hasMany(PostMedia::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function topLevelComments(): HasMany
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id')->oldest();
    }

    public function isLikedBy(?User $user): bool
    {
        if (!$user) {
            return false;
        }
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'parent_id');
    }

    public function reposts(): HasMany
    {
        return $this->hasMany(Post::class, 'parent_id');
    }

    public function poll(): HasOne
    {
        return $this->hasOne(Poll::class);
    }

    public function savedByUsers(): HasMany
    {
        return $this->hasMany(SavedPost::class);
    }

    public function hashtags(): BelongsToMany
    {
        return $this->belongsToMany(Hashtag::class)->withTimestamps();
    }

    public function getBodyHtmlAttribute(): string
    {
        if (!$this->body) return '';

        $text = e($this->body);

        $text = preg_replace(
            '/(?<!\w|&)#([\w]+)/u',
            '<a href="/search?q=%23$1" class="text-[#0058bc] hover:underline font-semibold" wire:navigate>#$1</a>',
            $text
        );

        $text = preg_replace(
            '/(?<!\w|&|@)@([\w\-\.]+)/u',
            '<a href="/profile/$1" class="text-[#0058bc] hover:underline font-semibold" wire:navigate>@$1</a>',
            $text
        );

        return nl2br($text);
    }

    public function scopeWithFeedRelations($query, ?int $userId = null): void
    {
        $query->with(['user', 'media', 'group', 'parent.user', 'poll.options'])
              ->withCount(['likes', 'comments', 'reposts']);

        if ($userId) {
            $query->withExists([
                'likes as is_liked_by_user' => fn ($q) => $q->where('likes.user_id', $userId),
                'savedByUsers as is_saved_by_user' => fn ($q) => $q->where('saved_posts.user_id', $userId),
            ]);
        }
    }
}
