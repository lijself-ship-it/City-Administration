<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExploreItemTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['explore_item_id', 'locale', 'name', 'description'];

    public function exploreItem(): BelongsTo
    {
        return $this->belongsTo(ExploreItem::class);
    }
}
