<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Searchable extends Model
{
    protected $fillable = [
        'searchable_type', 'searchable_id', 'locale',
        'title', 'content', 'url',
    ];

    /**
     * Get the parent searchable model (polymorphic).
     */
    public function searchable()
    {
        return $this->morphTo();
    }

    /**
     * Scope: search by keyword in title and content.
     */
    public function scopeSearch($query, string $keyword, ?string $locale = null)
    {
        $locale = $locale ?? app()->getLocale();

        return $query->where('locale', $locale)
            ->where(function ($q) use ($keyword) {
                $q->where('title', 'LIKE', "%{$keyword}%")
                  ->orWhere('content', 'LIKE', "%{$keyword}%");
            });
    }

    /**
     * Scope: filter by entity type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('searchable_type', $type);
    }

    /**
     * Rebuild the search index for a given model instance.
     */
    public static function reindex(Model $model, string $locale, array $data): self
    {
        return static::updateOrCreate(
            [
                'searchable_type' => get_class($model),
                'searchable_id' => $model->getKey(),
                'locale' => $locale,
            ],
            $data
        );
    }
}
