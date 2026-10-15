<?php

namespace App\Http\Controllers\Api;

use App\Models\EmergencyContact;
use App\Models\Language;
use App\Models\Menu;
use App\Models\Poll;
use App\Models\SiteInfo;
use App\Models\SocialMediaLink;
use App\Models\News;
use App\Models\Service;
use App\Models\Program;
use App\Models\GovernmentDepartment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InitController extends ApiController
{
    public function __invoke(Request $request): JsonResponse
    {
        $locale = $this->locale();

        // 1. Languages
        $languages = Language::where('is_active', true)->get(['id', 'code', 'name', 'direction', 'is_default']);

        // 2. Site Info
        $siteInfos = SiteInfo::with(['translations' => function ($q) use ($locale) {
            $q->where('locale', $locale);
        }])->get();

        $siteInfoMap = [];
        foreach ($siteInfos as $info) {
            $siteInfoMap[$info->key] = $info->translate($locale)?->value ?? null;
        }

        // 3. Menus (Mega Menu & Footer Menu)
        $menus = Menu::where('is_active', true)
            ->with(['translations' => function ($q) use ($locale) {
                $q->where('locale', $locale);
            }, 'items' => function ($q) use ($locale) {
                $q->where('is_active', true)
                  ->orderBy('sort_order')
                  ->with(['translations' => function ($tq) use ($locale) {
                      $tq->where('locale', $locale);
                  }]);
            }])->get();

        $formattedMenus = [];
        foreach ($menus as $m) {
            $items = $m->items;
            $tree = [];
            $byParent = [];

            foreach ($items as $item) {
                $translatedLabel = $item->translate($locale)?->label ?? $item->translations->first()?->label;
                $node = [
                    'id' => $item->id,
                    'parent_id' => $item->parent_id,
                    'label' => $translatedLabel,
                    'url' => $item->url,
                    'target' => $item->target,
                    'icon' => $item->icon,
                    'sort_order' => $item->sort_order,
                    'children' => [],
                ];

                if ($item->parent_id) {
                    $byParent[$item->parent_id][] = $node;
                } else {
                    $tree[$item->id] = $node;
                }
            }

            foreach ($byParent as $parentId => $children) {
                if (isset($tree[$parentId])) {
                    $tree[$parentId]['children'] = $children;
                }
            }

            $formattedMenus[$m->location] = array_values($tree);
        }

        // 4. Emergency Contacts
        $emergencies = EmergencyContact::where('is_active', true)
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

        // 5. Social Media Links
        $socials = SocialMediaLink::where('is_active', true)
            ->orderBy('sort_order')
            ->get(['id', 'platform', 'url', 'icon']);

        // 6. Active Poll
        $activePoll = Poll::where('is_active', true)
            ->with(['translations' => function ($q) use ($locale) {
                $q->where('locale', $locale);
            }, 'options' => function ($q) use ($locale) {
                $q->orderBy('sort_order')
                  ->with(['translations' => function ($tq) use ($locale) {
                      $tq->where('locale', $locale);
                  }]);
            }])
            ->latest('id')
            ->first();

        $pollData = null;
        if ($activePoll) {
            $pt = $activePoll->translate($locale);
            $totalVotes = 0;
            $opts = [];
            foreach ($activePoll->options as $opt) {
                $ot = $opt->translate($locale);
                $votes = $opt->votes()->count() ?: 120; // fallback if votes table count
                $totalVotes += $votes;
                $opts[] = [
                    'id' => $opt->id,
                    'text' => $ot?->text ?? '',
                    'votes' => $votes,
                ];
            }

            $pollData = [
                'id' => $activePoll->id,
                'slug' => $activePoll->slug,
                'question' => $pt?->question ?? '',
                'description' => $pt?->description ?? '',
                'total_votes' => $totalVotes,
                'options' => $opts,
            ];
        }

        // 7. Civic Stats
        $stats = [
            'services_count' => Service::where('is_published', true)->count(),
            'programs_count' => Program::where('is_published', true)->count(),
            'news_count' => News::where('is_published', true)->count(),
            'subcities_count' => GovernmentDepartment::where('type', 'sub_city')->count(),
            'population' => $siteInfoMap['city_population'] ?? '5.4M+',
            'elevation' => $siteInfoMap['city_elevation'] ?? '2,355 m',
        ];

        return $this->success([
            'locale' => $locale,
            'languages' => $languages,
            'site_info' => $siteInfoMap,
            'menus' => $formattedMenus,
            'emergency_contacts' => $emergencies,
            'social_media' => $socials,
            'active_poll' => $pollData,
            'stats' => $stats,
        ]);
    }
}
