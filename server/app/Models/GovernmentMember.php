<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GovernmentMember extends Model
{
    use HasTranslations, SoftDeletes;

    protected string $translationModel = GovernmentMemberTranslation::class;
    protected string $translationForeignKey = 'government_member_id';

    protected $fillable = [
        'type', 'photo', 'email', 'phone', 'social_links',
        'sort_order', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'social_links' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}
