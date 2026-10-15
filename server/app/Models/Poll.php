<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Poll extends Model
{
    use HasTranslations, SoftDeletes;

    protected string $translationModel = PollTranslation::class;
    protected string $translationForeignKey = 'poll_id';

    protected $fillable = [
        'slug', 'start_date', 'end_date',
        'is_active', 'is_multiple_choice',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'is_active' => 'boolean',
            'is_multiple_choice' => 'boolean',
        ];
    }

    public function options(): HasMany
    {
        return $this->hasMany(PollOption::class)->orderBy('sort_order');
    }

    public function votes(): HasMany
    {
        return $this->hasMany(PollVote::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeRunning($query)
    {
        return $query->active()
            ->where('start_date', '<=', now())
            ->where(function ($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', now());
            });
    }

    /**
     * Get total vote count for this poll.
     */
    public function getTotalVotesAttribute(): int
    {
        return $this->votes()->count();
    }

    /**
     * Check if an IP has already voted.
     */
    public function hasVoted(string $ipAddress): bool
    {
        return $this->votes()->where('ip_address', $ipAddress)->exists();
    }
}
