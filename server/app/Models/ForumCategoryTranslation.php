<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ForumCategoryTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['forum_category_id', 'locale', 'name', 'description'];

    public function forumCategory(): BelongsTo
    {
        return $this->belongsTo(ForumCategory::class);
    }
}
