<?php

namespace App\Http\Controllers\Api;

use App\Models\ExploreCategory;
use App\Models\ExploreItem;
use Illuminate\Http\JsonResponse;

class ExploreApiController extends ApiController
{
    /**
     * Get explore categories and featured destinations.
     */
    public function index(): JsonResponse
    {
        $locale = $this->locale();

        $categories = ExploreCategory::where('is_active', true)
            ->orderBy('sort_order')
            ->with(['translations' => function ($q) use ($locale) {
                $q->where('locale', $locale);
            }, 'items' => function ($q) use ($locale) {
                $q->where('is_published', true)
                  ->orderBy('sort_order')
                  ->with(['translations' => function ($tq) use ($locale) {
                      $tq->where('locale', $locale);
                  }]);
            }])
            ->get()
            ->map(function ($cat) use ($locale) {
                $t = $cat->translate($locale);
                return [
                    'id' => $cat->id,
                    'slug' => $cat->slug,
                    'featured_image' => $cat->featured_image,
                    'name' => $t?->name ?? '',
                    'description' => $t?->description ?? '',
                    'items' => $cat->items->map(function ($item) use ($locale) {
                        $it = $item->translate($locale);
                        return [
                            'id' => $item->id,
                            'slug' => $item->slug,
                            'featured_image' => $item->featured_image,
                            'name' => $it?->name ?? '',
                            'description' => $it?->description ?? '',
                        ];
                    }),
                ];
            });

        return $this->success($categories);
    }

    /**
     * Get single explore destination detail.
     */
    public function show(string $slug): JsonResponse
    {
        $locale = $this->locale();

        $item = ExploreItem::where('slug', $slug)
            ->where('is_published', true)
            ->with(['translations' => function ($q) use ($locale) {
                $q->where('locale', $locale);
            }, 'category' => function ($q) use ($locale) {
                $q->with(['translations' => function ($tq) use ($locale) {
                    $tq->where('locale', $locale);
                }]);
            }])
            ->first();

        if (!$item) {
            return $this->error('Destination not found', 404);
        }

        $t = $item->translate($locale);
        $catT = $item->category?->translate($locale);

        return $this->success([
            'id' => $item->id,
            'slug' => $item->slug,
            'featured_image' => $item->featured_image,
            'name' => $t?->name ?? '',
            'description' => $t?->description ?? '',
            'category' => [
                'name' => $catT?->name ?? '',
                'slug' => $item->category?->slug ?? '',
            ],
        ]);
    }
}
