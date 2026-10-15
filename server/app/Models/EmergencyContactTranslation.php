<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmergencyContactTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['emergency_contact_id', 'locale', 'title', 'description'];

    public function emergencyContact(): BelongsTo
    {
        return $this->belongsTo(EmergencyContact::class);
    }
}
