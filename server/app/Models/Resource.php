<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resource extends Model
{
    use HasTranslations, SoftDeletes;

    protected string $translationModel = ResourceTranslation::class;
    protected string $translationForeignKey = 'resource_id';

    protected $fillable = [
        'slug', 'file_path', 'file_type', 'file_size',
        'is_published', 'download_count', 'author_id',
    ];

    protected function casts(): array
    {
        return ['is_published' => 'boolean'];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function filters(): BelongsToMany
    {
        return $this->belongsToMany(ResourceFilter::class, 'resource_resource_filter');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Increment the download counter.
     */
    public function incrementDownloads(): void
    {
        $this->increment('download_count');
    }

    /**
     * Get human-readable file size.
     */
    public function getFormattedFileSizeAttribute(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }
}
