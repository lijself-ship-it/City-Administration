<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HeroSliderTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['hero_slider_id', 'locale', 'title', 'subtitle', 'button_text'];

    public function heroSlider(): BelongsTo
    {
        return $this->belongsTo(HeroSlider::class);
    }
}
