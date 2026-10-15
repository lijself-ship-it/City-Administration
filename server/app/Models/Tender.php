<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tender extends Model
{
    use HasTranslations, SoftDeletes;

    protected string $translationModel = TenderTranslation::class;
    protected string $translationForeignKey = 'tender_id';

    protected $fillable = [
        'slug', 'reference_number', 'opening_date', 'closing_date',
        'status', 'attachment', 'is_published', 'author_id',
    ];

    protected function casts(): array
    {
        return [
            'opening_date' => 'date',
            'closing_date' => 'date',
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
        return $query->where('status', 'open');
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }
}
