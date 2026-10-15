<?php

namespace App\Http\Controllers\Api;

use App\Models\Gallery;
use App\Models\Program;
use App\Models\Resource;
use App\Models\ResourceFilter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransparencyApiController extends ApiController
{
    /**
     * List strategic city programs and mega projects.
     */
    public function programs(): JsonResponse
    {
        $locale = $this->locale();

        $programs = Program::where('is_published', true)
            ->with(['translations' => function ($q) use ($locale) {
                $q->where('locale', $locale);
            }])
            ->get()
            ->map(function ($p) use ($locale) {
                $t = $p->translate($locale);
                return [
                    'id' => $p->id,
                    'slug' => $p->slug,
                    'featured_image' => $p->featured_image,
                    'start_date' => $p->start_date,
                    'end_date' => $p->end_date,
                    'budget' => $p->budget,
                    'progress_percentage' => $p->progress_percentage,
                    'status' => $p->status,
                    'title' => $t?->title ?? '',
                    'description' => $t?->description ?? '',
                    'objectives' => $t?->objectives ?? '',
                ];
            });

        return $this->success($programs);
    }

    /**
     * List public resources (PDFs, directives, budgets) with filter options.
     */
    public function resources(Request $request): JsonResponse
    {
        $locale = $this->locale();
        $query = Resource::where('is_published', true)->latest('created_at');

        if ($filterSlug = $request->query('filter')) {
            $query->whereHas('filters', function ($q) use ($filterSlug) {
                $q->where('slug', $filterSlug);
            });
        }

        $resources = $query->with(['translations' => function ($q) use ($locale) {
            $q->where('locale', $locale);
        }, 'filters' => function ($q) use ($locale) {
            $q->with(['translations' => function ($tq) use ($locale) {
                $tq->where('locale', $locale);
            }]);
        }])->get()->map(function ($r) use ($locale) {
            $t = $r->translate($locale);
            return [
                'id' => $r->id,
                'slug' => $r->slug,
                'file_path' => $r->file_path,
                'file_type' => $r->file_type,
                'file_size' => $r->file_size,
                'download_count' => $r->download_count,
                'title' => $t?->title ?? '',
                'description' => $t?->description ?? '',
                'filters' => $r->filters->map(function ($f) use ($locale) {
                    return [
                        'slug' => $f->slug,
                        'name' => $f->translate($locale)?->name ?? '',
                    ];
                }),
            ];
        });

        // Also fetch all available filters for UI pills
        $availableFilters = ResourceFilter::with(['translations' => function ($q) use ($locale) {
            $q->where('locale', $locale);
        }])->get()->map(function ($f) use ($locale) {
            return [
                'slug' => $f->slug,
                'name' => $f->translate($locale)?->name ?? '',
            ];
        });

        return $this->success([
            'resources' => $resources,
            'filters' => $availableFilters,
        ]);
    }

    /**
     * List photo/video galleries.
     */
    public function galleries(): JsonResponse
    {
        $locale = $this->locale();

        $galleries = Gallery::where('is_published', true)
            ->with(['translations' => function ($q) use ($locale) {
                $q->where('locale', $locale);
            }, 'items' => function ($q) use ($locale) {
                $q->with(['translations' => function ($tq) use ($locale) {
                    $tq->where('locale', $locale);
                }]);
            }])
            ->get()
            ->map(function ($g) use ($locale) {
                $t = $g->translate($locale);
                return [
                    'id' => $g->id,
                    'slug' => $g->slug,
                    'featured_image' => $g->featured_image,
                    'title' => $t?->title ?? '',
                    'description' => $t?->description ?? '',
                    'items_count' => $g->items->count(),
                ];
            });

        return $this->success($galleries);
    }
}
