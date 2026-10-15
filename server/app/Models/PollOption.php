<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PollOption extends Model
{
    use HasTranslations;

    protected string $translationModel = PollOptionTranslation::class;
    protected string $translationForeignKey = 'poll_option_id';

    protected $fillable = ['poll_id', 'sort_order'];

    public function poll(): BelongsTo
    {
        return $this->belongsTo(Poll::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(PollVote::class);
    }

    /**
     * Get vote count for this option.
     */
    public function getVoteCountAttribute(): int
    {
        return $this->votes()->count();
    }

    /**
     * Get vote percentage relative to total poll votes.
     */
    public function getPercentageAttribute(): float
    {
        $totalVotes = $this->poll->total_votes;
        if ($totalVotes === 0) {
            return 0;
        }
        return round(($this->vote_count / $totalVotes) * 100, 1);
    }
}
