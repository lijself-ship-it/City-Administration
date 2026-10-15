<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class SiteInfo extends Model
{
    use HasTranslations;

    protected string $translationModel = SiteInfoTranslation::class;
    protected string $translationForeignKey = 'site_info_id';

    protected $fillable = ['key', 'value'];

    /**
     * Get a site info value by key.
     */
    public static function getByKey(string $key): ?self
    {
        return static::where('key', $key)->first();
    }
}
