<?php

namespace App\Http\Controllers\Api;

use App\Models\Event;
use App\Models\News;
use App\Models\Tender;
use App\Models\Vacancy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MediaController extends ApiController
{
    /**
     * List News articles.
     */
    public function news(Request $request): JsonResponse
    {
        $locale = $this->locale();
        $query = News::where('is_published', true)->latest('published_at');

        if ($request->boolean('featured')) {
            $query->where('is_featured', true);
        }

        $news = $query->with(['translations' => function ($q) use ($locale) {
            $q->where('locale', $locale);
        }])->paginate($request->integer('per_page', 9));

        $items = collect($news->items())->map(function ($n) use ($locale) {
            $t = $n->translate($locale);
            return [
                'id' => $n->id,
                'slug' => $n->slug,
                'featured_image' => $n->featured_image,
                'is_featured' => $n->is_featured,
                'published_at' => $n->published_at,
                'views_count' => $n->views_count,
                'title' => $t?->title ?? '',
                'excerpt' => $t?->excerpt ?? '',
            ];
        });

        return $this->success($items, 'Success', 200, [
            'current_page' => $news->currentPage(),
            'last_page' => $news->lastPage(),
            'total' => $news->total(),
        ]);
    }

    /**
     * Single News detail by slug.
     */
    public function newsDetail(string $slug): JsonResponse
    {
        $locale = $this->locale();
        $news = News::where('slug', $slug)
            ->where('is_published', true)
            ->with(['translations' => function ($q) use ($locale) {
                $q->where('locale', $locale);
            }])
            ->first();

        if (!$news) {
            return $this->error('News article not found', 404);
        }

        $news->increment('views_count');
        $t = $news->translate($locale);

        return $this->success([
            'id' => $news->id,
            'slug' => $news->slug,
            'featured_image' => $news->featured_image,
            'is_featured' => $news->is_featured,
            'published_at' => $news->published_at,
            'views_count' => $news->views_count,
            'title' => $t?->title ?? '',
            'excerpt' => $t?->excerpt ?? '',
            'content' => $t?->content ?? '',
            'meta_title' => $t?->meta_title ?? '',
            'meta_description' => $t?->meta_description ?? '',
        ]);
    }

    /**
     * List Events.
     */
    public function events(Request $request): JsonResponse
    {
        $locale = $this->locale();
        $query = Event::where('is_published', true);

        if ($request->query('filter') === 'past') {
            $query->where('end_date', '<', now())->latest('start_date');
        } else {
            $query->where('start_date', '>=', now()->subDay())->orderBy('start_date');
        }

        $events = $query->with(['translations' => function ($q) use ($locale) {
            $q->where('locale', $locale);
        }])->get()->map(function ($ev) use ($locale) {
            $t = $ev->translate($locale);
            return [
                'id' => $ev->id,
                'slug' => $ev->slug,
                'featured_image' => $ev->featured_image,
                'start_date' => $ev->start_date,
                'end_date' => $ev->end_date,
                'title' => $t?->title ?? '',
                'description' => $t?->description ?? '',
                'venue' => $t?->venue ?? '',
            ];
        });

        return $this->success($events);
    }

    /**
     * List Vacancies.
     */
    public function vacancies(): JsonResponse
    {
        $locale = $this->locale();
        $vacancies = Vacancy::where('is_published', true)
            ->where('deadline', '>=', now()->toDateString())
            ->orderBy('deadline')
            ->with(['translations' => function ($q) use ($locale) {
                $q->where('locale', $locale);
            }])
            ->get()
            ->map(function ($v) use ($locale) {
                $t = $v->translate($locale);
                return [
                    'id' => $v->id,
                    'slug' => $v->slug,
                    'deadline' => $v->deadline,
                    'salary_range' => $v->salary_range,
                    'title' => $t?->title ?? '',
                    'description' => $t?->description ?? '',
                    'requirements' => $t?->requirements ?? '',
                    'department' => $t?->department ?? '',
                    'location' => $t?->location ?? '',
                ];
            });

        return $this->success($vacancies);
    }

    /**
     * List Tenders.
     */
    public function tenders(): JsonResponse
    {
        $locale = $this->locale();
        $tenders = Tender::where('is_published', true)
            ->orderBy('closing_date')
            ->with(['translations' => function ($q) use ($locale) {
                $q->where('locale', $locale);
            }])
            ->get()
            ->map(function ($tnd) use ($locale) {
                $t = $tnd->translate($locale);
                return [
                    'id' => $tnd->id,
                    'slug' => $tnd->slug,
                    'reference_number' => $tnd->reference_number,
                    'opening_date' => $tnd->opening_date,
                    'closing_date' => $tnd->closing_date,
                    'status' => $tnd->status,
                    'attachment' => $tnd->attachment,
                    'title' => $t?->title ?? '',
                    'description' => $t?->description ?? '',
                ];
            });

        return $this->success($tenders);
    }
}
