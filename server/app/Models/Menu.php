<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use HasTranslations, SoftDeletes;

    protected string $translationModel = MenuTranslation::class;
    protected string $translationForeignKey = 'menu_id';

    protected $fillable = ['location', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function items(): HasMany
    {
        return $this->hasMany(MenuItem::class)->whereNull('parent_id')->orderBy('sort_order');
    }

    public function allItems(): HasMany
    {
        return $this->hasMany(MenuItem::class)->orderBy('sort_order');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByLocation($query, string $location)
    {
        return $query->where('location', $location);
    }
}
