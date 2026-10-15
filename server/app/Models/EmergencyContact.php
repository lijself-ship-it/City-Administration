<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmergencyContact extends Model
{
    use HasTranslations, SoftDeletes;

    protected string $translationModel = EmergencyContactTranslation::class;
    protected string $translationForeignKey = 'emergency_contact_id';

    protected $fillable = ['phone', 'email', 'icon', 'sort_order', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}
