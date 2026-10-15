<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResourceTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['resource_id', 'locale', 'title', 'description'];

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }
}
