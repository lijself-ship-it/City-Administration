<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Checks if the authenticated user has the required permission(s).
 *
 * Usage in routes:
 *   ->middleware('permission:news.create')
 *   ->middleware('permission:news.edit,news.delete')  // requires ANY of these
 */
class CheckPermission
{
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        // Super Admin bypasses all permission checks
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        if (!$user->hasAnyPermission($permissions)) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to perform this action.',
            ], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
