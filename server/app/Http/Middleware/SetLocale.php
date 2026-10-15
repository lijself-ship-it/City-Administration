<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Sets the application locale based on the Accept-Language header
 * or the ?lang= query parameter.
 *
 * Priority: ?lang= query param > Accept-Language header > default locale
 */
class SetLocale
{
    private const SUPPORTED_LOCALES = ['en', 'am', 'om'];

    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->query('lang')
            ?? $request->query('locale')
            ?? $request->header('Accept-Language')
            ?? config('app.locale', 'en');

        // Extract primary language tag (e.g., "en-US" → "en")
        $locale = strtolower(substr($locale, 0, 2));

        if (!in_array($locale, self::SUPPORTED_LOCALES)) {
            $locale = config('app.locale', 'en');
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
