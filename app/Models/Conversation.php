<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = ['type'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('last_read_at')
            ->withTimestamps();
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function lastMessage(): HasMany
    {
        return $this->hasMany(Message::class)->latest()->take(1);
    }

    public function otherUser(User $user): ?User
    {
        return $this->users()->where('user_id', '!=', $user->id)->first();
    }

    public function unreadCountFor(User $user): int
    {
        $pivot = $this->users()->where('user_id', $user->id)->first()?->pivot;
        $lastRead = $pivot?->last_read_at;

        return $this->messages()
            ->where('user_id', '!=', $user->id)
            ->when($lastRead, fn ($q) => $q->where('created_at', '>', $lastRead))
            ->count();
    }
}
