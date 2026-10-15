<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class ApiController extends Controller
{
    /**
     * Return a standardized JSON success response.
     */
    protected function success(mixed $data = null, string $message = 'Success', int $status = 200, array $meta = []): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $data,
        ];

        if (!empty($meta)) {
            $response['meta'] = $meta;
        }

        return response()->json($response, $status);
    }

    /**
     * Return a standardized JSON error response.
     */
    protected function error(string $message = 'An error occurred', int $status = 400, array $errors = []): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $status);
    }

    /**
     * Get current requested locale.
     */
    protected function locale(): string
    {
        $loc = request()->query('lang') ?? request()->query('locale') ?? app()->getLocale();
        return in_array($loc, ['en', 'am', 'om']) ? $loc : 'en';
    }
}
