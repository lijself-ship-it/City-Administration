<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Blocks public API access when maintenance mode is enabled.
 * Authenticated admin users bypass this check.
 */
class CheckMaintenanceMode
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Setting::isMaintenanceMode()) {
            // Allow authenticated admin users to bypass
            $user = $request->user();
            if ($user && $user->isSuperAdmin()) {
                return $next($request);
            }

            $locale = app()->getLocale();
            $setting = Setting::getByKey('maintenance_mode');
            $message = $setting?->getTranslatedAttribute('value', $locale)
                ?? 'Site is under maintenance. Please check back later.';

            return response()->json([
                'success' => false,
                'message' => $message,
                'maintenance' => true,
            ], Response::HTTP_SERVICE_UNAVAILABLE);
        }

        return $next($request);
    }
}
