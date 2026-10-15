<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GalleryItem extends Model
{
    use HasTranslations;

    protected string $translationModel = GalleryItemTranslation::class;
    protected string $translationForeignKey = 'gallery_item_id';

    protected $fillable = ['gallery_id', 'type', 'file_path', 'video_url', 'sort_order'];

    public function gallery(): BelongsTo
    {
        return $this->belongsTo(Gallery::class);
    }

    public function isImage(): bool
    {
        return $this->type === 'image';
    }

    public function isVideo(): bool
    {
        return $this->type === 'video';
    }
}
