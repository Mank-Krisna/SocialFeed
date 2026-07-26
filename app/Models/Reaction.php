<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reaction extends Model
{
    use HasFactory;

    protected $table = 'reactions';

    protected $fillable = [
        'user_id',
        'post_id',
        'type',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public const TYPES = [
        'like' => '👍',
        'love' => '❤️',
        'haha' => '😆',
        'wow' => '😮',
        'sad' => '😢',
        'angry' => '😡',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function getEmojiAttribute(): string
    {
        return self::TYPES[$this->type] ?? '👍';
    }
}
