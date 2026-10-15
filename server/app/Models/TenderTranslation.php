<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TenderTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['tender_id', 'locale', 'title', 'description'];

    public function tender(): BelongsTo
    {
        return $this->belongsTo(Tender::class);
    }
}
