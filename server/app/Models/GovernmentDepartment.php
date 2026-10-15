<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class GovernmentDepartment extends Model
{
    use HasTranslations, SoftDeletes;

    protected string $translationModel = GovernmentDepartmentTranslation::class;
    protected string $translationForeignKey = 'government_department_id';

    protected $fillable = [
        'parent_id', 'type', 'image', 'phone', 'email',
        'sort_order', 'is_active',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(GovernmentDepartment::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(GovernmentDepartment::class, 'parent_id')->orderBy('sort_order');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeRootLevel($query)
    {
        return $query->whereNull('parent_id');
    }
}
