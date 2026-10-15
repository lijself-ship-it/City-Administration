<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SiteInfoTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['site_info_id', 'locale', 'value'];

    public function siteInfo(): BelongsTo
    {
        return $this->belongsTo(SiteInfo::class);
    }
}
