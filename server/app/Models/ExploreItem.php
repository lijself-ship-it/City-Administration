<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExploreItem extends Model
{
    use HasTranslations, SoftDeletes;

    protected string $translationModel = ExploreItemTranslation::class;
    protected string $translationForeignKey = 'explore_item_id';

    protected $fillable = [
        'explore_category_id', 'slug', 'featured_image',
        'location_lat', 'location_lng', 'sort_order', 'is_published',
    ];

    protected function casts(): array
    {
        return [
            'location_lat' => 'decimal:7',
            'location_lng' => 'decimal:7',
            'is_published' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ExploreCategory::class, 'explore_category_id');
    }

    public function sliders(): HasMany
    {
        return $this->hasMany(ExploreSlider::class)->orderBy('sort_order');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
}
