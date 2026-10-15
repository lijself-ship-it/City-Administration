<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ResourceFilter extends Model
{
    use HasTranslations;

    protected string $translationModel = ResourceFilterTranslation::class;
    protected string $translationForeignKey = 'resource_filter_id';

    protected $fillable = ['slug', 'sort_order'];

    public function resources(): BelongsToMany
    {
        return $this->belongsToMany(Resource::class, 'resource_resource_filter');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}
