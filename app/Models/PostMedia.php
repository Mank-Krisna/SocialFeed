<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PostMedia extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'file_path',
        'type',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function getUrlAttribute(): string
    {
        return Storage::url($this->file_path);
    }

    public function isVideo(): bool
    {
        return $this->type === 'video'
            || str_ends_with(strtolower($this->file_path), '.mp4')
            || str_ends_with(strtolower($this->file_path), '.mov')
            || str_ends_with(strtolower($this->file_path), '.avi')
            || str_ends_with(strtolower($this->file_path), '.webm')
            || str_ends_with(strtolower($this->file_path), '.mkv');
    }

    public static function detectType(UploadedFile $file): string
    {
        $mime = $file->getMimeType() ?? '';
        $ext = strtolower($file->getClientOriginalExtension());

        return str_contains($mime, 'video') || in_array($ext, ['mp4', 'mov', 'avi', 'webm', 'mkv', '3gp'])
            ? 'video'
            : 'image';
    }
}
