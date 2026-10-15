<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $fillable = [
        'code',
        'name',
        'direction',
        'is_default',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Scope: only active languages.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the default language.
     */
    public static function getDefault(): ?self
    {
        return static::where('is_default', true)->first();
    }
}
