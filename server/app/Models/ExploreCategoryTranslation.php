<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExploreCategoryTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['explore_category_id', 'locale', 'name', 'description'];

    public function exploreCategory(): BelongsTo
    {
        return $this->belongsTo(ExploreCategory::class);
    }
}
