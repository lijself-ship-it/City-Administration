<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Provides reusable multilingual translation support for Eloquent models.
 *
 * Usage:
 *   1. Use this trait in your model.
 *   2. Define the $translationModel property (FQCN of the translation model).
 *   3. Define the $translationForeignKey property (FK column in the translation table).
 *   4. Optionally define $translatedAttributes (array of translatable column names).
 *
 * Example:
 *   protected string $translationModel = NewsTranslation::class;
 *   protected string $translationForeignKey = 'news_id';
 *   protected array $translatedAttributes = ['title', 'excerpt', 'content'];
 */
trait HasTranslations
{
    /**
     * Get all translations for this model.
     */
    public function translations(): HasMany
    {
        return $this->hasMany($this->translationModel, $this->translationForeignKey);
    }

    /**
     * Get the translation for a specific locale.
     */
    public function translation(?string $locale = null): HasOne
    {
        $locale = $locale ?? app()->getLocale();

        return $this->hasOne($this->translationModel, $this->translationForeignKey)
            ->where('locale', $locale);
    }

    /**
     * Get a translated attribute value with fallback chain:
     * requested locale → app default locale → first available translation.
     */
    public function translate(?string $locale = null): mixed
    {
        $locale = $locale ?? app()->getLocale();

        // Try requested locale
        $translation = $this->translations->firstWhere('locale', $locale);

        // Fallback to default locale
        if (!$translation) {
            $defaultLocale = config('app.locale', 'en');
            $translation = $this->translations->firstWhere('locale', $defaultLocale);
        }

        // Fallback to any available translation
        if (!$translation) {
            $translation = $this->translations->first();
        }

        return $translation;
    }

    /**
     * Get a specific translated attribute value.
     */
    public function getTranslatedAttribute(string $attribute, ?string $locale = null): ?string
    {
        $translation = $this->translate($locale);

        return $translation?->{$attribute};
    }

    /**
     * Scope: eager load translations for a specific locale.
     */
    public function scopeWithTranslation($query, ?string $locale = null)
    {
        $locale = $locale ?? app()->getLocale();

        return $query->with(['translations' => function ($q) use ($locale) {
            $q->where('locale', $locale);
        }]);
    }

    /**
     * Scope: eager load all translations.
     */
    public function scopeWithAllTranslations($query)
    {
        return $query->with('translations');
    }

    /**
     * Check if a translation exists for the given locale.
     */
    public function hasTranslation(string $locale): bool
    {
        return $this->translations->contains('locale', $locale);
    }

    /**
     * Create or update a translation for the given locale.
     */
    public function setTranslation(string $locale, array $data): mixed
    {
        return $this->translations()->updateOrCreate(
            ['locale' => $locale],
            $data
        );
    }
}
