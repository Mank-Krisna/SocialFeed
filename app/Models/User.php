<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'bio',
        'avatar',
        'cover_photo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->is_admin ?? false;
    }

    /* ---------- Relationships ---------- */

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'user_id')->latest();
    }

    public function createdGroups(): HasMany
    {
        return $this->hasMany(Group::class, 'user_id');
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'group_user')->withPivot('role')->withTimestamps();
    }

    public function savedPosts(): HasMany
    {
        return $this->hasMany(SavedPost::class);
    }

    public function conversations(): BelongsToMany
    {
        return $this->belongsToMany(Conversation::class)->withPivot('last_read_at')->withTimestamps();
    }

    public function stories(): HasMany
    {
        return $this->hasMany(Story::class);
    }

    public function unreadMessagesCount(): int
    {
        return (int) DB::table('conversation_user')
            ->join('messages', 'messages.conversation_id', '=', 'conversation_user.conversation_id')
            ->where('conversation_user.user_id', $this->id)
            ->where('messages.user_id', '!=', $this->id)
            ->where(function ($q) {
                $q->whereNull('conversation_user.last_read_at')
                  ->orWhere('messages.created_at', '>', DB::raw('conversation_user.last_read_at'));
            })
            ->count();
    }

    /* ---------- Notification Helpers ---------- */

    public function unreadNotificationsCount(): int
    {
        return $this->notifications()->whereNull('read_at')->count();
    }

    /* ---------- Friendship Helpers ---------- */

    /**
     * Get all accepted friend IDs for this user.
     */
    public function friendIds(): array
    {
        $sent = Friendship::where('sender_id', $this->id)
            ->where('status', 'accepted')
            ->pluck('receiver_id');

        $received = Friendship::where('receiver_id', $this->id)
            ->where('status', 'accepted')
            ->pluck('sender_id');

        return $sent->merge($received)->unique()->toArray();
    }

    /**
     * Return the friendship status between this user and another user.
     * Returns: 'self', 'accepted', 'pending_sent', 'pending_received', or null
     */
    public function friendshipStatusWith(User $user): ?string
    {
        if ($this->id === $user->id) {
            return 'self';
        }

        $sent = Friendship::where('sender_id', $this->id)
            ->where('receiver_id', $user->id)
            ->first();

        if ($sent) {
            return $sent->status === 'accepted' ? 'accepted' : 'pending_sent';
        }

        $received = Friendship::where('sender_id', $user->id)
            ->where('receiver_id', $this->id)
            ->first();

        if ($received) {
            return $received->status === 'accepted' ? 'accepted' : 'pending_received';
        }

        return null;
    }

    /* ---------- Computed Attributes ---------- */

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return Storage::url($this->avatar);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=ffffff&background=0058bc';
    }

    public function getCoverPhotoUrlAttribute(): ?string
    {
        if ($this->cover_photo) {
            return Storage::url($this->cover_photo);
        }
        return null;
    }
}
