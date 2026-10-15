<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vacancy extends Model
{
    use HasTranslations, SoftDeletes;

    protected string $translationModel = VacancyTranslation::class;
    protected string $translationForeignKey = 'vacancy_id';

    protected $fillable = [
        'slug', 'deadline', 'salary_range', 'is_published', 'author_id',
    ];

    protected function casts(): array
    {
        return [
            'deadline' => 'date',
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

    public function scopeOpen($query)
    {
        return $query->where('deadline', '>=', now()->toDateString());
    }

    public function scopeExpired($query)
    {
        return $query->where('deadline', '<', now()->toDateString());
    }
}
