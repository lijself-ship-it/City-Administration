<?php

namespace App\Http\Controllers\Api;

use App\Models\EmergencyContact;
use App\Models\GovernmentDepartment;
use App\Models\GovernmentMember;
use App\Models\HeroSlider;
use App\Models\Page;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PortalController extends ApiController
{
    /**
     * Get active Hero Sliders.
     */
    public function heroSliders(): JsonResponse
    {
        $locale = $this->locale();

        $sliders = HeroSlider::where('is_active', true)
            ->orderBy('sort_order')
            ->with(['translations' => function ($q) use ($locale) {
                $q->where('locale', $locale);
            }])
            ->get()
            ->map(function ($s) use ($locale) {
                $t = $s->translate($locale);
                return [
                    'id' => $s->id,
                    'image' => $s->image,
                    'link_url' => $s->link_url,
                    'title' => $t?->title ?? '',
                    'subtitle' => $t?->subtitle ?? '',
                    'button_text' => $t?->button_text ?? '',
                ];
            });

        return $this->success($sliders);
    }

    /**
     * Get Emergency Contacts list.
     */
    public function emergencyContacts(): JsonResponse
    {
        $locale = $this->locale();

        $contacts = EmergencyContact::where('is_active', true)
            ->orderBy('sort_order')
            ->with(['translations' => function ($q) use ($locale) {
                $q->where('locale', $locale);
            }])
            ->get()
            ->map(function ($em) use ($locale) {
                $t = $em->translate($locale);
                return [
                    'id' => $em->id,
                    'phone' => $em->phone,
                    'email' => $em->email,
                    'icon' => $em->icon,
                    'title' => $t?->title ?? '',
                    'description' => $t?->description ?? '',
                ];
            });

        return $this->success($contacts);
    }

    /**
     * Get a Page by slug.
     */
    public function page(string $slug): JsonResponse
    {
        $locale = $this->locale();

        $page = Page::where('slug', $slug)
            ->where('is_published', true)
            ->with(['translations' => function ($q) use ($locale) {
                $q->where('locale', $locale);
            }])
            ->first();

        if (!$page) {
            return $this->error('Page not found', 404);
        }

        $t = $page->translate($locale);

        return $this->success([
            'id' => $page->id,
            'slug' => $page->slug,
            'type' => $page->type,
            'featured_image' => $page->featured_image,
            'title' => $t?->title ?? '',
            'content' => $t?->content ?? '',
            'meta_title' => $t?->meta_title ?? '',
            'meta_description' => $t?->meta_description ?? '',
        ]);
    }

    /**
     * Get Government Members (Mayor, Cabinet, Council).
     */
    public function governmentMembers(Request $request): JsonResponse
    {
        $locale = $this->locale();
        $query = GovernmentMember::where('is_active', true)->orderBy('sort_order');

        if ($type = $request->query('type')) {
            $query->where('type', $type);
        }

        $members = $query->with(['translations' => function ($q) use ($locale) {
            $q->where('locale', $locale);
        }])->get()->map(function ($m) use ($locale) {
            $t = $m->translate($locale);
            return [
                'id' => $m->id,
                'type' => $m->type,
                'photo' => $m->photo,
                'email' => $m->email,
                'phone' => $m->phone,
                'social_links' => $m->social_links,
                'name' => $t?->name ?? '',
                'title' => $t?->title ?? '',
                'bio' => $t?->bio ?? '',
            ];
        });

        return $this->success($members);
    }

    /**
     * Get Government Departments & Sub-Cities.
     */
    public function governmentDepartments(Request $request): JsonResponse
    {
        $locale = $this->locale();
        $query = GovernmentDepartment::where('is_active', true)->orderBy('sort_order');

        if ($type = $request->query('type')) {
            $query->where('type', $type);
        }

        $departments = $query->with(['translations' => function ($q) use ($locale) {
            $q->where('locale', $locale);
        }, 'children' => function ($q) use ($locale) {
            $q->where('is_active', true)->with(['translations' => function ($tq) use ($locale) {
                $tq->where('locale', $locale);
            }]);
        }])->get()->map(function ($d) use ($locale) {
            $t = $d->translate($locale);
            return [
                'id' => $d->id,
                'parent_id' => $d->parent_id,
                'type' => $d->type,
                'image' => $d->image,
                'phone' => $d->phone,
                'email' => $d->email,
                'name' => $t?->name ?? '',
                'description' => $t?->description ?? '',
                'address' => $t?->address ?? '',
                'children' => $d->children->map(function ($c) use ($locale) {
                    $ct = $c->translate($locale);
                    return [
                        'id' => $c->id,
                        'name' => $ct?->name ?? '',
                        'description' => $ct?->description ?? '',
                    ];
                }),
            ];
        });

        return $this->success($departments);
    }
}
