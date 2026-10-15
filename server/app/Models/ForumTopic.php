<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ForumTopic extends Model
{
    use HasTranslations, SoftDeletes;

    protected string $translationModel = ForumTopicTranslation::class;
    protected string $translationForeignKey = 'forum_topic_id';

    protected $fillable = [
        'forum_category_id', 'user_id', 'slug',
        'is_pinned', 'is_locked', 'views_count',
    ];

    protected function casts(): array
    {
        return [
            'is_pinned' => 'boolean',
            'is_locked' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ForumCategory::class, 'forum_category_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function replies(): HasMany
    {
        return $this->hasMany(ForumReply::class)->oldest();
    }

    public function rootReplies(): HasMany
    {
        return $this->hasMany(ForumReply::class)->whereNull('parent_id')->oldest();
    }

    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }

    public function incrementViews(): void
    {
        $this->increment('views_count');
    }
}
