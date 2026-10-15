<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GovernmentMemberTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['government_member_id', 'locale', 'name', 'title', 'bio'];

    public function governmentMember(): BelongsTo
    {
        return $this->belongsTo(GovernmentMember::class);
    }
}
