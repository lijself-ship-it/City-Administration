<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceStepTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['service_step_id', 'locale', 'title', 'description'];

    public function serviceStep(): BelongsTo
    {
        return $this->belongsTo(ServiceStep::class);
    }
}
