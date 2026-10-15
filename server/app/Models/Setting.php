<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasTranslations;

    protected string $translationModel = SettingTranslation::class;
    protected string $translationForeignKey = 'setting_id';

    protected $fillable = ['key', 'value'];

    /**
     * Get a setting value by key.
     */
    public static function getByKey(string $key): ?self
    {
        return static::where('key', $key)->first();
    }

    /**
     * Get a setting's raw value by key.
     */
    public static function getValue(string $key, mixed $default = null): mixed
    {
        $setting = static::getByKey($key);
        return $setting?->value ?? $default;
    }

    /**
     * Set a setting value by key.
     */
    public static function setValue(string $key, mixed $value): self
    {
        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    /**
     * Check if maintenance mode is enabled.
     */
    public static function isMaintenanceMode(): bool
    {
        return (bool) static::getValue('maintenance_mode', false);
    }
}
