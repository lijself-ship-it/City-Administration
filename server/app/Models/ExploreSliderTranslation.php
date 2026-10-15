<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExploreSliderTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['explore_slider_id', 'locale', 'caption'];

    public function exploreSlider(): BelongsTo
    {
        return $this->belongsTo(ExploreSlider::class);
    }
}
