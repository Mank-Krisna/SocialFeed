<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'description',
        'photo',
        'type',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'group_user')->withPivot('role')->withTimestamps();
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class)->latest();
    }

    public function isMember(?User $user): bool
    {
        if (!$user) return false;
        return $this->members()->where('user_id', $user->id)->exists();
    }

    public function isAdmin(?User $user): bool
    {
        if (!$user) return false;
        return $this->members()->where('user_id', $user->id)->wherePivot('role', 'admin')->exists();
    }

    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo) {
            return Storage::url($this->photo);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=ffffff&background=0058bc';
    }
}
