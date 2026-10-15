<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasTranslations;

    protected string $translationModel = ContactTranslation::class;
    protected string $translationForeignKey = 'contact_id';

    protected $fillable = [
        'phone', 'email', 'fax',
        'location_lat', 'location_lng', 'map_embed_url',
    ];

    protected function casts(): array
    {
        return [
            'location_lat' => 'decimal:7',
            'location_lng' => 'decimal:7',
        ];
    }
}
