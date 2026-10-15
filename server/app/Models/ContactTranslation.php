<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['contact_id', 'locale', 'address', 'office_name', 'working_hours'];

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }
}
