<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExploreSlider extends Model
{
    use HasTranslations;

    protected string $translationModel = ExploreSliderTranslation::class;
    protected string $translationForeignKey = 'explore_slider_id';

    protected $fillable = ['explore_item_id', 'image', 'sort_order', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function exploreItem(): BelongsTo
    {
        return $this->belongsTo(ExploreItem::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeGlobal($query)
    {
        return $query->whereNull('explore_item_id');
    }
}
