<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PollOptionTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['poll_option_id', 'locale', 'text'];

    public function pollOption(): BelongsTo
    {
        return $this->belongsTo(PollOption::class);
    }
}
