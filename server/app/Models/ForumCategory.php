<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ForumCategory extends Model
{
    use HasTranslations;

    protected string $translationModel = ForumCategoryTranslation::class;
    protected string $translationForeignKey = 'forum_category_id';

    protected $fillable = ['slug', 'sort_order', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function topics(): HasMany
    {
        return $this->hasMany(ForumTopic::class)->latest();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}
