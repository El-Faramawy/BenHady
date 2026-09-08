<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Support\Facades\App;

trait HasLocalizedAttributes
{
    /**
     * Get a localized attribute value based on the current application locale.
     * Checks attribute_{locale}, falls back to Arabic ({attribute}_ar), then English ({attribute}_en).
     */
    public function getLocalized(string $attribute): ?string
    {
        $locale = App::getLocale() === 'en' ? 'en' : 'ar';
        $fallback = $locale === 'en' ? 'ar' : 'en';

        $primaryKey = "{$attribute}_{$locale}";
        $fallbackKey = "{$attribute}_{$fallback}";

        return $this->getAttribute($primaryKey) ?? $this->getAttribute($fallbackKey);
    }
}
