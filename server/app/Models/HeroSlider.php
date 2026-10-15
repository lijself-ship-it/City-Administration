<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HeroSlider extends Model
{
    use HasTranslations, SoftDeletes;

    protected string $translationModel = HeroSliderTranslation::class;
    protected string $translationForeignKey = 'hero_slider_id';

    protected $fillable = ['image', 'link_url', 'sort_order', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}
