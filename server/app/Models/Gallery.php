<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gallery extends Model
{
    use HasTranslations, SoftDeletes;

    protected string $translationModel = GalleryTranslation::class;
    protected string $translationForeignKey = 'gallery_id';

    protected $fillable = ['slug', 'featured_image', 'sort_order', 'is_published'];

    protected function casts(): array
    {
        return ['is_published' => 'boolean'];
    }

    public function items(): HasMany
    {
        return $this->hasMany(GalleryItem::class)->orderBy('sort_order');
    }

    public function images(): HasMany
    {
        return $this->items()->where('type', 'image');
    }

    public function videos(): HasMany
    {
        return $this->items()->where('type', 'video');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}
