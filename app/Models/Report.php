<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Report extends Model
{
    /**
     * Valid report reasons.
     */
    const REASONS = [
        'spam'           => 'Spam',
        'harassment'     => 'Pelecehan / Intimidasi',
        'hate_speech'    => 'Ujaran Kebencian / SARA',
        'misinformation' => 'Informasi Salah / Hoaks',
        'violence'       => 'Konten Kekerasan',
        'nudity'         => 'Konten Dewasa / NSFW',
        'other'          => 'Lainnya',
    ];

    const STATUS_PENDING   = 'pending';
    const STATUS_RESOLVED  = 'resolved';
    const STATUS_DISMISSED = 'dismissed';

    protected $fillable = [
        'user_id',
        'reportable_type',
        'reportable_id',
        'reason',
        'status',
        'resolved_by',
        'resolved_at',
        'notes',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    /**
     * The user who submitted the report.
     */
    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * The moderator who resolved/dismissed the report.
     */
    public function resolver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    /**
     * The reported content (Post, Comment, or User).
     */
    public function reportable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope: only pending reports.
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope: only resolved reports.
     */
    public function scopeResolved($query)
    {
        return $query->where('status', self::STATUS_RESOLVED);
    }

    public function getReasonLabelAttribute(): string
    {
        return self::REASONS[$this->reason] ?? ucfirst($this->reason);
    }
}
