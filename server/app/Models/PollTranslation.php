<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PollTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['poll_id', 'locale', 'question', 'description'];

    public function poll(): BelongsTo
    {
        return $this->belongsTo(Poll::class);
    }
}
