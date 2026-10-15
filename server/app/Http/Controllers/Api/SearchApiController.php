<?php

namespace App\Http\Controllers\Api;

use App\Models\Searchable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchApiController extends ApiController
{
    /**
     * Unified Global Search.
     */
    public function search(Request $request): JsonResponse
    {
        $query = trim($request->query('q', ''));
        if (empty($query) || strlen($query) < 2) {
            return $this->success([]);
        }

        $locale = $this->locale();

        $results = Searchable::where(function ($q) use ($query) {
            $q->where('title', 'LIKE', "%{$query}%")
              ->orWhere('content', 'LIKE', "%{$query}%");
        })
        ->where(function ($q) use ($locale) {
            $q->where('locale', $locale)->orWhere('locale', 'en');
        })
        ->limit(15)
        ->get(['id', 'searchable_type', 'searchable_id', 'title', 'content', 'url']);

        $formatted = $results->map(function ($r) {
            $type = class_basename($r->searchable_type);
            return [
                'id' => $r->id,
                'type' => $type,
                'title' => $r->title,
                'snippet' => mb_substr(strip_tags($r->content), 0, 140) . '...',
                'url' => $r->url,
            ];
        });

        return $this->success($formatted);
    }
}
