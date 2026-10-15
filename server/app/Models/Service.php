<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasTranslations, SoftDeletes;

    protected string $translationModel = ServiceTranslation::class;
    protected string $translationForeignKey = 'service_id';

    protected $fillable = [
        'slug', 'type', 'icon', 'featured_image',
        'sort_order', 'is_published',
    ];

    protected function casts(): array
    {
        return ['is_published' => 'boolean'];
    }

    /**
     * Steps for this service (only relevant when type = 'step').
     */
    public function steps(): HasMany
    {
        return $this->hasMany(ServiceStep::class)->orderBy('step_number');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Check if this is a step-based service.
     */
    public function isStepService(): bool
    {
        return $this->type === 'step';
    }
}
