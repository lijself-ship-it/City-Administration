<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasTranslations, SoftDeletes;

    protected string $translationModel = EventTranslation::class;
    protected string $translationForeignKey = 'event_id';

    protected $fillable = [
        'slug', 'featured_image', 'start_date', 'end_date',
        'location_lat', 'location_lng', 'is_published', 'author_id',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'location_lat' => 'decimal:7',
            'location_lng' => 'decimal:7',
            'is_published' => 'boolean',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>=', now())->orderBy('start_date');
    }

    public function scopePast($query)
    {
        return $query->where('start_date', '<', now())->orderByDesc('start_date');
    }
}
