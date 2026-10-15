<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Program extends Model
{
    use HasTranslations, SoftDeletes;

    protected string $translationModel = ProgramTranslation::class;
    protected string $translationForeignKey = 'program_id';

    protected $fillable = [
        'slug', 'featured_image', 'start_date', 'end_date',
        'budget', 'progress_percentage', 'status', 'is_published', 'author_id',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'budget' => 'decimal:2',
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

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeOngoing($query)
    {
        return $query->where('status', 'ongoing');
    }
}
