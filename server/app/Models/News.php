<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class News extends Model
{
    use HasTranslations, SoftDeletes;

    protected string $translationModel = NewsTranslation::class;
    protected string $translationForeignKey = 'news_id';

    protected $fillable = [
        'slug', 'featured_image', 'is_featured', 'is_published',
        'published_at', 'views_count', 'author_id',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeLatestPublished($query)
    {
        return $query->published()->orderByDesc('published_at');
    }

    /**
     * Increment the view counter.
     */
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }
}
