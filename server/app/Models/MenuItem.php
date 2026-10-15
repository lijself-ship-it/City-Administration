<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class MenuItem extends Model
{
    use HasTranslations;

    protected string $translationModel = MenuItemTranslation::class;
    protected string $translationForeignKey = 'menu_item_id';

    protected $fillable = [
        'menu_id', 'parent_id', 'linkable_type', 'linkable_id',
        'url', 'target', 'icon', 'sort_order', 'is_active',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('sort_order');
    }

    /**
     * Polymorphic link target (Page, etc.)
     */
    public function linkable(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeRootLevel($query)
    {
        return $query->whereNull('parent_id');
    }
}
