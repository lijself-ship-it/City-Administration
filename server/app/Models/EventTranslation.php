<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['event_id', 'locale', 'title', 'description', 'venue'];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
