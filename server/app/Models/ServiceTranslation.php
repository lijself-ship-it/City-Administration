<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['service_id', 'locale', 'title', 'description'];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
