<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GovernmentDepartmentTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['government_department_id', 'locale', 'name', 'description', 'address'];

    public function governmentDepartment(): BelongsTo
    {
        return $this->belongsTo(GovernmentDepartment::class);
    }
}
