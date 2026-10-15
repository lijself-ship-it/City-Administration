<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['menu_id', 'locale', 'title'];

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }
}
