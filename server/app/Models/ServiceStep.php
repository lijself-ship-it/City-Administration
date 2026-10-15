<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceStep extends Model
{
    use HasTranslations;

    protected string $translationModel = ServiceStepTranslation::class;
    protected string $translationForeignKey = 'service_step_id';

    protected $fillable = ['service_id', 'step_number', 'icon'];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
