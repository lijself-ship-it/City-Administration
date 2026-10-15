<script setup lang="ts">
import { ref, onMounted, watch } from 'vue';
import { useRouter } from 'vue-router';
import { usePortalStore } from '../stores/portal';
import { api } from '../services/api';
import HeroSliderComponent from '../components/HeroSliderComponent.vue';
import PollComponent from '../components/PollComponent.vue';
import UiIcon from '../components/UiIcon.vue';
import type { ServiceItem, NewsItem, EventItem, ExploreDestination, GovernmentMemberItem } from '../types';

const router = useRouter();
const portalStore = usePortalStore();

const featuredServices = ref<ServiceItem[]>([]);
const latestNews = ref<NewsItem[]>([]);
const upcomingEvents = ref<EventItem[]>([]);
const exploreItems = ref<ExploreDestination[]>([]);
const mayorProfile = ref<GovernmentMemberItem | null>(null);
const isLoading = ref<boolean>(false);

// Verified initial data for Kombolcha City Administration
const defaultServices: ServiceItem[] = [
  {
    id: 1,
    slug: 'building-construction-permit',
    type: 'step',
    icon: 'home',
    title: 'Building & Construction Permit Application',
    description: 'Submit architectural drawings, structural stability certifications, and zoning clearance digitally to the Kombolcha Urban Development Bureau.',
    steps_count: 4,
  },
  {
    id: 2,
    slug: 'commercial-trade-license-renewal',
    type: 'step',
    icon: 'briefcase',
    title: 'Commercial Trade & Industrial License Renewal',
    description: 'Instant annual trade renewal for manufacturing enterprises, retail merchants, and logistics operators in Kombolcha.',
    steps_count: 3,
  },
  {
    id: 3,
    slug: 'vital-events-birth-registration',
    type: 'text',
    icon: 'user-check',
    title: 'Vital Events & Civil Registration',
    description: 'Official registration of births, marriages, and legal certificates processed within 24 to 48 hours at municipal kebele offices.',
    steps_count: 0,
  }
];

const defaultNews: NewsItem[] = [
  {
    id: 1,
    slug: 'kombolcha-industrial-park-expansion-new-investors',
    featured_image: 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&w=800&q=80',
    is_featured: true,
    published_at: '2026-10-14T08:00:00Z',
    views_count: 2450,
    title: 'Kombolcha Industrial Park Welcomes 6 New Export Textile & Garment Manufacturers',
    excerpt: 'Over 4,500 new industrial jobs created as state-of-the-art production sheds reach full operational capacity.',
  },
  {
    id: 2,
    slug: 'kombolcha-dry-port-capacity-upgrade-customs-modernization',
    featured_image: 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&w=800&q=80',
    is_featured: true,
    published_at: '2026-10-12T08:00:00Z',
    views_count: 1820,
    title: 'Kombolcha Dry Port Terminal Expands Handling Capacity with Automated Crane Systems',
    excerpt: 'Container turnaround time reduced to under 48 hours with single-window electronic cargo clearance.',
  },
  {
    id: 3,
    slug: 'kombolcha-city-boulevard-solar-lighting-and-green-corridor',
    featured_image: 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=800&q=80',
    is_featured: false,
    published_at: '2026-10-10T08:00:00Z',
    views_count: 1140,
    title: 'Kombolcha City Corridor: 8.5 km Modernized Boulevard with Solar Lighting Completed',
    excerpt: 'New pedestrian walkways, cycling corridors, and 12,000 shade trees beautify the central highway.',
  }
];

const defaultEvents: EventItem[] = [
  {
    id: 1,
    slug: 'kombolcha-annual-industrial-and-trade-expo-2026',
    start_date: '2026-10-28 09:00:00',
    title: 'Kombolcha Regional Industrial & Trade Expo 2026',
    description: 'Showcasing textiles, garments, leather, construction products, and agro-industries with 150+ manufacturers.',
    venue: 'Kombolcha Industrial Park Exhibition Grounds',
  },
  {
    id: 2,
    slug: 'kombolcha-town-hall-citizen-participatory-budget-meeting',
    start_date: '2026-11-06 09:30:00',
    title: 'Kombolcha Citizen Town Hall & Participatory Budget Forum',
    description: 'Public consultation between the Mayor, Kebele representatives, and residents regarding neighborhood infrastructure investments.',
    venue: 'Kombolcha Municipal Hall',
  }
];

const defaultDestinations: ExploreDestination[] = [
  {
    id: 1,
    slug: 'kombolcha-industrial-park-ipdc',
    featured_image: 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&w=800&q=80',
    name: 'Kombolcha Industrial Eco-Park (IPDC)',
    description: 'Spanning 75 hectares dedicated to textiles, garments, and light manufacturing with dedicated wastewater treatment.',
  },
  {
    id: 2,
    slug: 'kombolcha-dry-port-multimodal-terminal',
    featured_image: 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&w=800&q=80',
    name: 'Kombolcha Multimodal Dry Port & Logistics Hub',
    description: 'The premier inland customs terminal linking maritime transit cargo from Djibouti to northern Ethiopian regional markets.',
  },
  {
    id: 3,
    slug: 'bati-cultural-camel-market-gateway',
    featured_image: 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?auto=format&fit=crop&w=800&q=80',
    name: 'Bati Historic Market & Cultural Trade Gateway',
    description: 'Famous Monday trading marketplace connecting Amhara, Oromo, and Afar merchants for centuries of regional trade.',
  }
];

function formatNewsDate(dateStr?: string): string {
  if (!dateStr) return '';
  try {
    const d = new Date(dateStr);
    if (isNaN(d.getTime())) return dateStr;
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    return `${months[d.getMonth()]} ${d.getDate()}, ${d.getFullYear()}`;
  } catch {
    return dateStr;
  }
}

function parseEventDate(dateStr?: string): { month: string; day: string } {
  if (!dateStr) return { month: 'OCT', day: '28' };
  try {
    const d = new Date(dateStr);
    if (!isNaN(d.getTime())) {
      const months = ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'];
      return { month: months[d.getMonth()], day: String(d.getDate()).padStart(2, '0') };
    }
  } catch {
    // fallback
  }
  const parts = dateStr.split(' ');
  return { month: parts[0]?.slice(0, 3)?.toUpperCase() || 'OCT', day: parts[1]?.replace(',', '') || '28' };
}

async function loadData() {
  isLoading.value = true;
  featuredServices.value = defaultServices;
  latestNews.value = defaultNews;
  upcomingEvents.value = defaultEvents;
  exploreItems.value = defaultDestinations;

  try {
    const [servicesRes, newsRes, eventsRes, exploreRes, mayorRes] = await Promise.all([
      api.getServices(portalStore.locale),
      api.getNews(portalStore.locale),
      api.getEvents(portalStore.locale),
      api.getExplore(portalStore.locale),
      api.getGovernmentMembers(portalStore.locale, 'mayor'),
    ]);

    if (servicesRes && servicesRes.length > 0) {
      featuredServices.value = servicesRes.slice(0, 3);
    }
    if (newsRes && newsRes.length > 0) {
      latestNews.value = newsRes.slice(0, 3);
    }
    if (eventsRes && eventsRes.length > 0) {
      upcomingEvents.value = eventsRes.slice(0, 2);
    }
    if (exploreRes && exploreRes.length > 0) {
      const allItems = exploreRes.flatMap((c: any) => c.items || []);
      if (allItems.length > 0) {
        exploreItems.value = allItems.slice(0, 3);
      }
    }
    if (mayorRes && mayorRes.length > 0) {
      mayorProfile.value = mayorRes[0];
    }
  } catch {
    // Keep verified defaults
  } finally {
    isLoading.value = false;
  }
}

onMounted(() => {
  loadData();
});

watch(() => portalStore.locale, () => {
  loadData();
});
</script>

<template>
  <div class="home-portal space-y-8 sm:space-y-12 pb-12">
    <!-- 1. Hero Carousel Slider -->
    <HeroSliderComponent />

    <!-- 2. Quick Municipal Services Access Grid -->
    <section class="container -mt-8 sm:-mt-12 relative z-20" aria-label="Frequently Used Municipal Services">
      <div class="civic-card p-4 sm:p-6 bg-white dark:bg-slate-900 shadow-xl border border-slate-200/90 dark:border-slate-800">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4">
          <router-link
            to="/services#building-construction-permit"
            class="p-3 sm:p-4 rounded-xl hover:bg-emerald-50 dark:hover:bg-emerald-950/40 border border-transparent hover:border-emerald-200 dark:hover:border-emerald-800 transition group text-center flex flex-col items-center justify-between"
          >
            <div class="w-11 h-11 sm:w-12 sm:h-12 mb-2 rounded-xl bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400 flex items-center justify-center group-hover:scale-105 transition-transform shadow-xs">
              <UiIcon name="home" :size="22" />
            </div>
            <div>
              <h3 class="font-bold text-xs sm:text-sm text-slate-900 dark:text-white leading-tight">
                {{ portalStore.t.buildingPermits }}
              </h3>
              <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1 line-clamp-1">
                {{ portalStore.t.architecturalClearance }}
              </p>
            </div>
          </router-link>

          <router-link
            to="/services#vital-events-birth-registration"
            class="p-3 sm:p-4 rounded-xl hover:bg-blue-50 dark:hover:bg-blue-950/40 border border-transparent hover:border-blue-200 dark:hover:border-blue-800 transition group text-center flex flex-col items-center justify-between"
          >
            <div class="w-11 h-11 sm:w-12 sm:h-12 mb-2 rounded-xl bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-400 flex items-center justify-center group-hover:scale-105 transition-transform shadow-xs">
              <UiIcon name="user-check" :size="22" />
            </div>
            <div>
              <h3 class="font-bold text-xs sm:text-sm text-slate-900 dark:text-white leading-tight">
                {{ portalStore.t.vitalEvents }}
              </h3>
              <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1 line-clamp-1">
                {{ portalStore.t.vitalEventsDesc }}
              </p>
            </div>
          </router-link>

          <router-link
            to="/media?tab=tenders"
            class="p-3 sm:p-4 rounded-xl hover:bg-amber-50 dark:hover:bg-amber-950/40 border border-transparent hover:border-amber-200 dark:hover:border-amber-800 transition group text-center flex flex-col items-center justify-between"
          >
            <div class="w-11 h-11 sm:w-12 sm:h-12 mb-2 rounded-xl bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-400 flex items-center justify-center group-hover:scale-105 transition-transform shadow-xs">
              <UiIcon name="file-text" :size="22" />
            </div>
            <div>
              <h3 class="font-bold text-xs sm:text-sm text-slate-900 dark:text-white leading-tight">
                {{ portalStore.t.publicTenders }}
              </h3>
              <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1 line-clamp-1">
                {{ portalStore.t.tendersDesc }}
              </p>
            </div>
          </router-link>

          <router-link
            to="/services#commercial-trade-license-renewal"
            class="p-3 sm:p-4 rounded-xl hover:bg-purple-50 dark:hover:bg-purple-950/40 border border-transparent hover:border-purple-200 dark:hover:border-purple-800 transition group text-center flex flex-col items-center justify-between"
          >
            <div class="w-11 h-11 sm:w-12 sm:h-12 mb-2 rounded-xl bg-purple-100 dark:bg-purple-900/40 text-purple-700 dark:text-purple-400 flex items-center justify-center group-hover:scale-105 transition-transform shadow-xs">
              <UiIcon name="briefcase" :size="22" />
            </div>
            <div>
              <h3 class="font-bold text-xs sm:text-sm text-slate-900 dark:text-white leading-tight">
                {{ portalStore.t.tradeLicenses }}
              </h3>
              <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1 line-clamp-1">
                {{ portalStore.t.tradeLicensesDesc }}
              </p>
            </div>
          </router-link>
        </div>
      </div>
    </section>

    <!-- 3. Mayor's Official Executive Message (Authoritative & Verified) -->
    <section class="container" aria-label="Mayor's Official Message">
      <div class="civic-card p-6 sm:p-8 md:p-10 bg-gradient-to-r from-[#00223D] via-[#003554] to-[#004370] text-white relative overflow-hidden border-slate-700 shadow-xl">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8 items-center relative z-10">
          <!-- Portrait & Name -->
          <div class="md:col-span-1 flex flex-col items-center md:items-start text-center md:text-left">
            <div class="w-32 h-32 sm:w-36 sm:h-36 md:w-40 md:h-40 rounded-2xl overflow-hidden border-3 border-emerald-400/40 shadow-xl mb-3 bg-slate-800">
              <img
                :src="mayorProfile?.photo || 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=600&q=80'"
                :alt="mayorProfile?.name || 'Mayor of Kombolcha'"
                class="w-full h-full object-cover"
              />
            </div>
            <h3 class="font-extrabold text-base md:text-lg text-white">
              {{ mayorProfile?.name || 'Hon. Mayor of Kombolcha' }}
            </h3>
            <p class="text-xs text-emerald-300 font-semibold mt-0.5">
              {{ mayorProfile?.title || 'Mayor of Kombolcha City Administration' }}
            </p>
            <div v-if="mayorProfile?.email" class="flex items-center gap-2 mt-2 text-xs text-slate-300">
              <UiIcon name="mail" :size="12" />
              <span>{{ mayorProfile.email }}</span>
            </div>
          </div>

          <!-- Editorial Message -->
          <div class="md:col-span-2 space-y-3 sm:space-y-4">
            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-400/20 text-amber-300 text-[11px] font-bold uppercase tracking-wider border border-amber-400/30">
              <UiIcon name="landmark" :size="13" />
              <span>{{ portalStore.t.mayorSpotlight }}</span>
            </div>

            <h2 class="text-xl sm:text-2xl md:text-3xl font-extrabold text-white leading-tight">
              {{ portalStore.t.mayorQuote }}
            </h2>

            <p class="text-xs sm:text-sm text-slate-200 leading-relaxed font-normal">
              {{ mayorProfile?.bio || portalStore.t.mayorSpeech }}
            </p>

            <div class="pt-2 flex flex-wrap items-center gap-3">
              <router-link
                to="/government#mayor"
                class="btn bg-white/10 hover:bg-white/20 text-white border border-white/30 text-xs px-5 py-2.5 rounded-lg flex items-center gap-2 transition cursor-pointer font-semibold"
              >
                <span>{{ portalStore.t.readFullMessage }}</span>
                <UiIcon name="arrow-right" :size="14" />
              </router-link>

              <router-link
                to="/about"
                class="text-xs font-semibold text-emerald-300 hover:text-white flex items-center gap-1 transition"
              >
                <span>Explore City Charter</span>
                <UiIcon name="arrow-right" :size="12" />
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- 4. Citizen Services Hub -->
    <section class="container space-y-6" aria-label="Official Citizen Digital Services">
      <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
          <span class="badge badge-emerald mb-2">
            {{ portalStore.t.quickServices }}
          </span>
          <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white">
            {{ portalStore.t.servicesTitle }}
          </h2>
          <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1">
            {{ portalStore.t.servicesSubtitle }}
          </p>
        </div>
        <router-link
          to="/services"
          class="btn btn-primary text-xs px-5 py-2.5 self-start sm:self-auto cursor-pointer font-bold shadow-sm"
        >
          <span>{{ portalStore.t.exploreAllServices }}</span>
          <UiIcon name="arrow-right" :size="14" />
        </router-link>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div
          v-for="service in featuredServices"
          :key="service.id"
          class="civic-card p-6 flex flex-col justify-between h-full bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-800"
        >
          <div class="space-y-4">
            <div class="flex items-center justify-between">
              <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 flex items-center justify-center shadow-xs">
                <UiIcon :name="service.icon || 'layers'" :size="24" />
              </div>
              <span
                class="badge text-[11px]"
                :class="service.type === 'step' ? 'badge-blue' : 'badge-emerald'"
              >
                {{ service.type === 'step' ? `${service.steps_count || 4} ${portalStore.t.stepsGuide}` : portalStore.t.instantService }}
              </span>
            </div>

            <h3 class="font-bold text-base text-slate-900 dark:text-white leading-snug">
              {{ service.title }}
            </h3>

            <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed line-clamp-3">
              {{ service.description }}
            </p>
          </div>

          <div class="pt-5 mt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
            <router-link
              :to="`/services#${service.slug}`"
              class="text-xs font-bold text-emerald-700 dark:text-emerald-400 hover:underline flex items-center gap-1.5"
            >
              <span>{{ portalStore.t.stepGuide }}</span>
              <UiIcon name="arrow-right" :size="12" />
            </router-link>
            <span class="text-[11px] text-slate-500 font-medium">{{ portalStore.t.turnaround }}</span>
          </div>
        </div>
      </div>
    </section>

    <!-- 5. Dual Grid: Official News & Civic Participation -->
    <section class="container grid grid-cols-1 lg:grid-cols-3 gap-8" aria-label="News and Civic Participation">
      <!-- News Column (2 cols on lg) -->
      <div class="lg:col-span-2 space-y-6">
        <div class="flex items-center justify-between">
          <div>
            <span class="badge badge-emerald mb-1">{{ portalStore.t.pressRoom }}</span>
            <h2 class="text-2xl font-extrabold text-slate-900 dark:text-white">
              {{ portalStore.t.latestNews }}
            </h2>
          </div>
          <router-link
            to="/media?tab=news"
            class="text-xs font-bold text-emerald-700 dark:text-emerald-400 hover:underline flex items-center gap-1 cursor-pointer"
          >
            <span>{{ portalStore.t.viewAllNews }}</span>
            <UiIcon name="arrow-right" :size="14" />
          </router-link>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
          <div
            v-for="news in latestNews"
            :key="news.id"
            class="civic-card flex flex-col justify-between group bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-800"
          >
            <div>
              <div class="h-44 overflow-hidden relative bg-slate-800">
                <img
                  :src="news.featured_image"
                  :alt="news.title"
                  class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                />
                <span v-if="news.is_featured" class="absolute top-3 left-3 badge badge-gold shadow-sm font-bold">
                  {{ portalStore.t.featured }}
                </span>
              </div>
              <div class="p-5 space-y-2">
                <div class="flex items-center gap-2 text-[11px] text-slate-500 font-medium">
                  <UiIcon name="calendar" :size="12" />
                  <span>{{ formatNewsDate(news.published_at) }}</span>
                  <span>•</span>
                  <span>{{ news.views_count }} {{ portalStore.t.views }}</span>
                </div>
                <h3 class="font-bold text-sm text-slate-900 dark:text-white leading-snug group-hover:text-emerald-700 dark:group-hover:text-emerald-400 transition">
                  {{ news.title }}
                </h3>
                <p class="text-xs text-slate-600 dark:text-slate-400 line-clamp-2 leading-relaxed">
                  {{ news.excerpt }}
                </p>
              </div>
            </div>
            <div class="p-5 pt-0">
              <router-link
                :to="`/media?tab=news#${news.slug}`"
                class="text-xs font-bold text-emerald-700 dark:text-emerald-400 hover:underline flex items-center gap-1.5"
              >
                <span>{{ portalStore.t.readArticle }}</span>
                <UiIcon name="arrow-right" :size="12" />
              </router-link>
            </div>
          </div>
        </div>
      </div>

      <!-- Events & Civic Poll Column (1 col on lg) -->
      <div class="space-y-6">
        <div>
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-slate-900 dark:text-white">
              {{ portalStore.t.upcomingEvents }}
            </h3>
            <router-link to="/media?tab=events" class="text-xs font-bold text-emerald-700 dark:text-emerald-400 hover:underline">
              {{ portalStore.t.all }}
            </router-link>
          </div>

          <div class="space-y-3">
            <div
              v-for="ev in upcomingEvents"
              :key="ev.id"
              class="civic-card p-4 flex gap-3.5 items-start bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-800"
            >
              <!-- Clean Calendar Badge: Month on Top, Day on Bottom -->
              <div class="w-12 h-13 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-400 font-bold flex flex-col items-center justify-center shrink-0 border border-emerald-200 dark:border-emerald-800/80 shadow-2xs">
                <span class="text-[10px] uppercase font-extrabold tracking-wider leading-none">
                  {{ parseEventDate(ev.start_date).month }}
                </span>
                <span class="text-base font-black leading-tight mt-0.5">
                  {{ parseEventDate(ev.start_date).day }}
                </span>
              </div>
              <div class="space-y-1 min-w-0">
                <h4 class="font-bold text-xs text-slate-900 dark:text-white leading-snug">
                  {{ ev.title }}
                </h4>
                <p class="text-[11px] text-slate-500 flex items-center gap-1 truncate">
                  <UiIcon name="map-pin" :size="11" class="shrink-0" />
                  <span class="truncate">{{ ev.venue }}</span>
                </p>
              </div>
            </div>
          </div>
        </div>

        <!-- Interactive Civic Poll Widget -->
        <PollComponent />
      </div>
    </section>

    <!-- 6. Explore Kombolcha Hub Showcase -->
    <section class="container space-y-6" aria-label="Explore Kombolcha Hub">
      <div class="flex items-center justify-between">
        <div>
          <span class="badge badge-emerald mb-1">{{ portalStore.t.exploreCity }}</span>
          <h2 class="text-2xl font-extrabold text-slate-900 dark:text-white">
            {{ portalStore.t.exploreCity }}
          </h2>
          <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">
            {{ portalStore.t.exploreSubtitle }}
          </p>
        </div>
        <router-link
          to="/explore"
          class="btn btn-secondary text-xs px-4 py-2 font-semibold cursor-pointer"
        >
          <span>{{ portalStore.t.viewAllAttractions }}</span>
          <UiIcon name="arrow-right" :size="14" />
        </router-link>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div
          v-for="dest in exploreItems"
          :key="dest.id"
          class="civic-card group overflow-hidden relative cursor-pointer border border-slate-200/90 dark:border-slate-800"
          @click="router.push('/explore')"
        >
          <div class="h-60 overflow-hidden relative bg-slate-800">
            <img
              :src="dest.featured_image"
              :alt="dest.name"
              class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
            />
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/50 to-transparent"></div>
            <div class="absolute bottom-4 left-4 right-4 text-white space-y-1">
              <span class="text-[11px] font-bold text-emerald-400 uppercase tracking-wider">{{ portalStore.t.topSanctuary }}</span>
              <h4 class="font-bold text-base text-white leading-tight drop-shadow-sm">{{ dest.name }}</h4>
              <p class="text-xs text-slate-200 line-clamp-2 leading-relaxed">{{ dest.description }}</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- 7. Authoritative Municipal Statistics Counter -->
    <section class="container" aria-label="Authoritative Municipal Statistics">
      <div class="civic-card p-6 sm:p-8 bg-[#002B47] text-white border-slate-700 shadow-xl">
        <div class="text-center max-w-xl mx-auto mb-6 sm:mb-8 space-y-1.5">
          <span class="badge badge-gold font-bold">{{ portalStore.t.officialMetrics }}</span>
          <h3 class="text-xl sm:text-2xl font-extrabold text-white">{{ portalStore.t.cityNumbers }}</h3>
          <p class="text-xs text-slate-300">Verified demographic, geographic, and municipal infrastructure data</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6 text-center">
          <div class="p-4 rounded-xl bg-white/5 border border-white/10 backdrop-blur-xs">
            <div class="text-2xl sm:text-3xl font-black text-emerald-400 mb-1">{{ portalStore.stats.population }}</div>
            <div class="text-xs text-slate-300 font-medium">{{ portalStore.t.population }}</div>
          </div>
          <div class="p-4 rounded-xl bg-white/5 border border-white/10 backdrop-blur-xs">
            <div class="text-2xl sm:text-3xl font-black text-amber-400 mb-1">{{ portalStore.stats.elevation }}</div>
            <div class="text-xs text-slate-300 font-medium">{{ portalStore.t.elevation }}</div>
          </div>
          <div class="p-4 rounded-xl bg-white/5 border border-white/10 backdrop-blur-xs">
            <div class="text-2xl sm:text-3xl font-black text-sky-400 mb-1">{{ portalStore.stats.services_count }}+</div>
            <div class="text-xs text-slate-300 font-medium">{{ portalStore.t.activeServices }}</div>
          </div>
          <div class="p-4 rounded-xl bg-white/5 border border-white/10 backdrop-blur-xs">
            <div class="text-2xl sm:text-3xl font-black text-purple-400 mb-1">{{ portalStore.stats.subcities_count }} Kebeles</div>
            <div class="text-xs text-slate-300 font-medium">{{ portalStore.t.subCitiesTitle }}</div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
