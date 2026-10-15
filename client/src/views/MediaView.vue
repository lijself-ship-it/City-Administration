<script setup lang="ts">
import { ref, onMounted, watch } from 'vue';
import { useRoute } from 'vue-router';
import { usePortalStore } from '../stores/portal';
import { api } from '../services/api';
import UiIcon from '../components/UiIcon.vue';
import type { NewsItem, EventItem, VacancyItem, TenderItem } from '../types';

const route = useRoute();
const portalStore = usePortalStore();

const activeTab = ref<'news' | 'events' | 'vacancies' | 'tenders'>('news');
const selectedNews = ref<NewsItem | null>(null);

const newsList = ref<NewsItem[]>([
  {
    id: 1,
    slug: 'corridor-development-phase-two-completion',
    featured_image: 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?auto=format&fit=crop&w=800&q=80',
    is_featured: true,
    published_at: 'Oct 14, 2026',
    views_count: 1420,
    title: 'City Inaugurates Phase 2 Green Urban Corridor Project',
    excerpt: 'Over 12 kilometers of dedicated pedestrian walkways, cycling routes, and LED lighting opened to the public.',
    content: 'The metropolitan corridor development initiative marks a transformational leap towards eco-friendly transportation, enhanced neighborhood safety, and vibrant public recreational spaces across the capital. Pedestrian bridges and bicycle parking hubs are now active.'
  },
  {
    id: 2,
    slug: 'smart-city-single-window-digital-launch',
    featured_image: 'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&w=800&q=80',
    is_featured: true,
    published_at: 'Oct 12, 2026',
    views_count: 980,
    title: 'Unified Citizen Portal Consolidates 45 Municipal Services',
    excerpt: 'Citizens can now access birth certificates, construction clearances, and tax payments without standing in queues.',
    content: 'The Mayor formally launched the unified civic platform integrating national digital ID (Fayda) and real-time mobile payment gateways, ensuring radical efficiency and anti-corruption transparency.'
  },
  {
    id: 3,
    slug: 'annual-green-legacy-urban-tree-planting-campaign',
    featured_image: 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=800&q=80',
    is_featured: false,
    published_at: 'Oct 10, 2026',
    views_count: 730,
    title: 'Green Legacy: 5 Million Seedlings Planted Across Catchment Hills',
    excerpt: 'Community mobilization drives reforestation across Entoto hills, riverbanks, and school compounds.',
    content: 'The annual plantation effort safeguards the capital watershed basin while boosting urban forest coverage above 25%.'
  }
]);

const eventsList = ref<EventItem[]>([
  {
    id: 1,
    slug: 'international-mayors-urban-forum-2026',
    featured_image: 'https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=800&q=80',
    start_date: 'Oct 28, 2026',
    end_date: 'Oct 31, 2026',
    title: 'International Mayors Urban Resilience Summit',
    description: 'Over 40 African and global municipal leaders convene to exchange policies on smart mobility and green corridors.',
    venue: 'Millennium Hall, African Avenue',
  },
  {
    id: 2,
    slug: 'city-arts-and-cultural-festival',
    featured_image: 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&w=800&q=80',
    start_date: 'Nov 05, 2026',
    end_date: 'Nov 07, 2026',
    title: 'Capital Cultural Heritage & Street Arts Carnival',
    description: 'Live traditional music, photography exhibitions, culinary showcases, and craft markets celebrating cultural unity.',
    venue: 'Meskel Square & Friendship Park',
  }
]);

const vacanciesList = ref<VacancyItem[]>([
  {
    id: 1,
    slug: 'senior-full-stack-cloud-architect',
    title: 'Senior Full Stack Cloud Software Architect',
    deadline: 'Oct 29, 2026',
    salary_range: 'ETB 35,000 - 55,000 / month',
    department: 'Innovation & Smart City Bureau',
    location: 'City Hall, Churchill Avenue',
    description: 'Lead the architecture of municipal high-availability cloud microservices and public API gateways.',
    requirements: 'BSc/MSc in Software Engineering or Computer Science, 5+ years experience in Vue, Laravel, Docker, and PostgreSQL.',
  },
  {
    id: 2,
    slug: 'urban-planning-gis-lead-specialist',
    title: 'Urban Planning & Geographic Information Systems (GIS) Lead',
    deadline: 'Nov 02, 2026',
    salary_range: 'ETB 30,000 - 45,000 / month',
    department: 'Land Development & Urban Planning Agency',
    location: 'Kirkos Sub-City Office',
    description: 'Direct spatial analysis, satellite imagery verification, and master plan cadastre updates.',
    requirements: 'Degree in Urban Planning, Geomatics, or GIS with proficiency in ArcGIS and spatial mapping.',
  }
]);

const tendersList = ref<TenderItem[]>([
  {
    id: 1,
    slug: 'tender-smart-traffic-sensors-corridor-four',
    reference_number: 'AAC-IT-2026-089',
    opening_date: 'Oct 15, 2026',
    closing_date: 'Nov 09, 2026',
    status: 'open',
    title: 'Procurement & Installation of Adaptive AI Traffic Sensors',
    description: 'Supply and commissioning of intelligent dynamic intersection cameras, vehicular count radar, and centralized monitoring.',
  },
  {
    id: 2,
    slug: 'tender-solar-street-lighting-sub-cities',
    reference_number: 'AAC-ENG-2026-104',
    opening_date: 'Oct 15, 2026',
    closing_date: 'Nov 14, 2026',
    status: 'open',
    title: 'Turnkey Solar LED Streetlight Modernization across 4 Sub-Cities',
    description: 'Design, supply, battery bank installation, and warranty service for 8,500 solar street poles across key arterial routes.',
  }
]);

async function loadMediaData() {
  try {
    const n = await api.getNews(portalStore.locale);
    if (n && n.length > 0) newsList.value = n;

    const ev = await api.getEvents(portalStore.locale);
    if (ev && ev.length > 0) eventsList.value = ev;

    const v = await api.getVacancies(portalStore.locale);
    if (v && v.length > 0) vacanciesList.value = v;

    const t = await api.getTenders(portalStore.locale);
    if (t && t.length > 0) tendersList.value = t;
  } catch {
    // Keep defaults
  }
}

watch(() => route.query.tab, (tab) => {
  if (tab && ['news', 'events', 'vacancies', 'tenders'].includes(tab as string)) {
    activeTab.value = tab as any;
  }
}, { immediate: true });

function handleApply(title: string) {
  window.alert('Civil service application portal initiated for: ' + title);
}

onMounted(() => {
  loadMediaData();
});

watch(() => portalStore.locale, () => {
  loadMediaData();
});
</script>

<template>
  <div class="media-portal container section-spacing space-y-10">
    <!-- Header -->
    <div class="max-w-3xl space-y-2">
      <span class="badge badge-emerald">{{ portalStore.t.pressRoom }}</span>
      <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 dark:text-white">
        {{ portalStore.t.mediaCenterTitle }}
      </h1>
      <p class="text-sm md:text-base text-slate-500 dark:text-slate-400">
        {{ portalStore.t.mediaCenterSubtitle }}
      </p>
    </div>

    <!-- Media Tabs Navigation -->
    <div class="flex items-center gap-2 overflow-x-auto pb-2 border-b border-slate-200 dark:border-slate-800">
      <button
        v-for="tab in [
          { key: 'news', label: portalStore.t.latestNews, count: newsList.length, icon: 'newspaper' },
          { key: 'events', label: portalStore.t.upcomingEvents, count: eventsList.length, icon: 'calendar' },
          { key: 'vacancies', label: portalStore.t.vacanciesTitle, count: vacanciesList.length, icon: 'briefcase' },
          { key: 'tenders', label: portalStore.t.tendersTitle, count: tendersList.length, icon: 'file-text' },
        ]"
        :key="tab.key"
        @click="activeTab = tab.key as any"
        class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs md:text-sm font-bold whitespace-nowrap transition"
        :class="activeTab === tab.key
          ? 'bg-emerald-600 text-white shadow-md'
          : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700'"
      >
        <UiIcon :name="tab.icon" :size="16" />
        <span>{{ tab.label }}</span>
        <span class="px-1.5 py-0.5 rounded-full text-[10px]" :class="activeTab === tab.key ? 'bg-white/20' : 'bg-slate-200 dark:bg-slate-700'">
          {{ tab.count }}
        </span>
      </button>
    </div>

    <!-- Tab 1: News Room -->
    <div v-if="activeTab === 'news'" class="grid grid-cols-1 md:grid-cols-3 gap-6 animate-fade-in">
      <div
        v-for="news in newsList"
        :key="news.id"
        class="civic-card flex flex-col justify-between group cursor-pointer"
        @click="selectedNews = news"
      >
        <div>
          <div class="h-48 overflow-hidden relative">
            <img :src="news.featured_image" :alt="news.title" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
            <span v-if="news.is_featured" class="absolute top-3 left-3 badge badge-gold shadow">{{ portalStore.t.featured }}</span>
          </div>
          <div class="p-6 space-y-2">
            <div class="flex items-center gap-2 text-[11px] text-slate-400 font-medium">
              <span>{{ news.published_at }}</span>
              <span>•</span>
              <span>{{ news.views_count }} {{ portalStore.t.views }}</span>
            </div>
            <h3 class="font-bold text-base text-slate-900 dark:text-white group-hover:text-emerald-600 transition leading-snug">
              {{ news.title }}
            </h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2">
              {{ news.excerpt }}
            </p>
          </div>
        </div>
        <div class="p-6 pt-0">
          <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 flex items-center gap-1">
            {{ portalStore.t.readArticle }} <UiIcon name="arrow-right" :size="12" />
          </span>
        </div>
      </div>
    </div>

    <!-- Tab 2: Events Calendar -->
    <div v-else-if="activeTab === 'events'" class="grid grid-cols-1 md:grid-cols-2 gap-6 animate-fade-in">
      <div
        v-for="ev in eventsList"
        :key="ev.id"
        class="civic-card p-6 flex flex-col md:flex-row gap-6 items-start"
      >
        <div class="w-20 h-20 rounded-2xl bg-gradient-to-tr from-emerald-600 to-emerald-500 text-white flex flex-col items-center justify-center shrink-0 shadow-lg">
          <span class="text-xs font-medium uppercase tracking-wider">Date</span>
          <span class="text-xl font-black">{{ ev.start_date.split(' ')[1]?.replace(',', '') || '28' }}</span>
          <span class="text-[10px] uppercase font-bold">{{ ev.start_date.split(' ')[0] }}</span>
        </div>
        <div class="space-y-2">
          <div class="badge badge-emerald">{{ portalStore.t.upcomingEvents }}</div>
          <h3 class="font-bold text-lg text-slate-900 dark:text-white leading-snug">{{ ev.title }}</h3>
          <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">{{ ev.description }}</p>
          <div class="pt-2 text-xs text-slate-500 flex items-center gap-2">
            <UiIcon name="map-pin" :size="13" class="text-emerald-600" />
            <span>{{ ev.venue }}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Tab 3: Vacancies / Civil Service Jobs -->
    <div v-else-if="activeTab === 'vacancies'" class="space-y-4 animate-fade-in">
      <div
        v-for="vac in vacanciesList"
        :key="vac.id"
        class="civic-card p-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-6"
      >
        <div class="space-y-2 max-w-2xl">
          <div class="flex items-center gap-2">
            <span class="badge badge-blue">{{ vac.department }}</span>
            <span class="text-xs text-red-600 font-semibold flex items-center gap-1">
              <UiIcon name="clock" :size="12" />
              {{ portalStore.t.deadline }}: {{ vac.deadline }}
            </span>
          </div>
          <h3 class="font-bold text-lg text-slate-900 dark:text-white">{{ vac.title }}</h3>
          <p class="text-xs text-slate-500 dark:text-slate-400">{{ vac.description }}</p>
          <div class="text-xs text-slate-600 dark:text-slate-300 pt-1">
            <strong>{{ portalStore.t.jobRequirements }}:</strong> {{ vac.requirements }}
          </div>
        </div>

        <div class="shrink-0 flex flex-col sm:items-end gap-2 w-full md:w-auto">
          <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/40 px-3 py-1 rounded-lg">
            {{ vac.salary_range }}
          </span>
          <button
            class="btn btn-primary text-xs px-5 py-2.5 rounded-xl w-full sm:w-auto"
            @click="handleApply(vac.title)"
          >
            {{ portalStore.t.applyForJob }}
          </button>
        </div>
      </div>
    </div>

    <!-- Tab 4: Public Procurement & Tenders -->
    <div v-else class="space-y-4 animate-fade-in">
      <div
        v-for="tnd in tendersList"
        :key="tnd.id"
        class="civic-card p-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-6"
      >
        <div class="space-y-2 max-w-2xl">
          <div class="flex items-center gap-2">
            <span class="badge badge-gold font-mono">{{ tnd.reference_number }}</span>
            <span class="badge badge-emerald uppercase font-bold">{{ tnd.status }}</span>
          </div>
          <h3 class="font-bold text-lg text-slate-900 dark:text-white">{{ tnd.title }}</h3>
          <p class="text-xs text-slate-500 dark:text-slate-400">{{ tnd.description }}</p>
          <div class="flex items-center gap-4 text-xs text-slate-500 pt-1">
            <span><strong>{{ portalStore.t.openingDate }}:</strong> {{ tnd.opening_date }}</span>
            <span><strong>{{ portalStore.t.closingDate }}:</strong> {{ tnd.closing_date }}</span>
          </div>
        </div>

        <div class="shrink-0 flex items-center gap-3 w-full md:w-auto">
          <button class="btn btn-secondary text-xs px-4 py-2.5 rounded-xl flex items-center gap-2">
            <UiIcon name="download" :size="14" />
            <span>{{ portalStore.t.downloadTenderDoc }}</span>
          </button>
        </div>
      </div>
    </div>

    <!-- Single News Reading Modal -->
    <transition name="modal-fade">
      <div
        v-if="selectedNews"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-md"
        @click.self="selectedNews = null"
      >
        <div class="w-full max-w-2xl max-h-[90vh] overflow-y-auto bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-800 p-6 md:p-8 space-y-4">
          <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-3">
            <span class="badge badge-emerald">{{ portalStore.t.pressRoom }}</span>
            <button @click="selectedNews = null" class="p-1 text-slate-400 hover:text-slate-600">
              <UiIcon name="x" :size="18" />
            </button>
          </div>
          <div class="h-64 rounded-xl overflow-hidden">
            <img :src="selectedNews.featured_image" :alt="selectedNews.title" class="w-full h-full object-cover" />
          </div>
          <div class="text-xs text-slate-400 flex items-center gap-2">
            <span>{{ selectedNews.published_at }}</span>
            <span>•</span>
            <span>{{ selectedNews.views_count }} {{ portalStore.t.views }}</span>
          </div>
          <h2 class="text-2xl font-bold text-slate-900 dark:text-white leading-tight">
            {{ selectedNews.title }}
          </h2>
          <p class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed whitespace-pre-line">
            {{ selectedNews.content || selectedNews.excerpt }}
          </p>
        </div>
      </div>
    </transition>
  </div>
</template>
