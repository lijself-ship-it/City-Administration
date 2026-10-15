<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResourceFilterTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['resource_filter_id', 'locale', 'name'];

    public function resourceFilter(): BelongsTo
    {
        return $this->belongsTo(ResourceFilter::class);
    }
}
