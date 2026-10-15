<?php

namespace App\Http\Controllers\Api;

use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ServiceApiController extends ApiController
{
    /**
     * List all published services.
     */
    public function index(Request $request): JsonResponse
    {
        $locale = $this->locale();
        $query = Service::where('is_published', true)->orderBy('sort_order');

        if ($type = $request->query('type')) {
            $query->where('type', $type);
        }

        $services = $query->with(['translations' => function ($q) use ($locale) {
            $q->where('locale', $locale);
        }, 'steps' => function ($q) use ($locale) {
            $q->orderBy('step_number')->with(['translations' => function ($tq) use ($locale) {
                $tq->where('locale', $locale);
            }]);
        }])->get()->map(function ($s) use ($locale) {
            $t = $s->translate($locale);
            return [
                'id' => $s->id,
                'slug' => $s->slug,
                'type' => $s->type,
                'icon' => $s->icon,
                'featured_image' => $s->featured_image,
                'title' => $t?->title ?? '',
                'description' => $t?->description ?? '',
                'steps_count' => $s->steps->count(),
            ];
        });

        return $this->success($services);
    }

    /**
     * Get single service detail with step-by-step guidance.
     */
    public function show(string $slug): JsonResponse
    {
        $locale = $this->locale();

        $service = Service::where('slug', $slug)
            ->where('is_published', true)
            ->with(['translations' => function ($q) use ($locale) {
                $q->where('locale', $locale);
            }, 'steps' => function ($q) use ($locale) {
                $q->orderBy('step_number')->with(['translations' => function ($tq) use ($locale) {
                    $tq->where('locale', $locale);
                }]);
            }])
            ->first();

        if (!$service) {
            return $this->error('Service not found', 404);
        }

        $t = $service->translate($locale);
        $steps = $service->steps->map(function ($step) use ($locale) {
            $st = $step->translate($locale);
            return [
                'id' => $step->id,
                'step_number' => $step->step_number,
                'icon' => $step->icon,
                'title' => $st?->title ?? '',
                'description' => $st?->description ?? '',
            ];
        });

        return $this->success([
            'id' => $service->id,
            'slug' => $service->slug,
            'type' => $service->type,
            'icon' => $service->icon,
            'featured_image' => $service->featured_image,
            'title' => $t?->title ?? '',
            'description' => $t?->description ?? '',
            'steps' => $steps,
        ]);
    }
}
