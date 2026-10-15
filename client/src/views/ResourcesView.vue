<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue';
import { usePortalStore } from '../stores/portal';
import { api } from '../services/api';
import UiIcon from '../components/UiIcon.vue';
import type { ResourceItem } from '../types';

const portalStore = usePortalStore();

const searchQuery = ref('');
const selectedFilter = ref('all');

const availableFilters = computed(() => [
  { slug: 'all', name: portalStore.t.all },
  { slug: 'directives', name: portalStore.t.resourcesTitle },
  { slug: 'reports', name: portalStore.t.officialMetrics },
]);

const resources = ref<ResourceItem[]>([
  {
    id: 1,
    slug: 'directive-urban-building-code-2026',
    file_path: '/documents/building-code-regulation-2026.pdf',
    file_type: 'pdf',
    file_size: 3984500,
    download_count: 842,
    title: 'City Building Code & Fire Safety Directive No. 42/2026',
    description: 'Mandatory technical specifications for high-rise commercial structures, disabled access, and seismic load compliance.',
    filters: [{ slug: 'directives', name: 'Directives & Regulations' }],
  },
  {
    id: 2,
    slug: 'annual-city-performance-budget-audit-2025-2026',
    file_path: '/documents/city-annual-budget-audit-report-2026.pdf',
    file_type: 'pdf',
    file_size: 5452100,
    download_count: 615,
    title: 'Approved Annual Municipal Budget & Capital Expenditure Report',
    description: 'Complete fiscal breakdown across road construction, water supply, public schooling, and healthcare facilities.',
    filters: [{ slug: 'reports', name: 'Annual Budget & Audit Reports' }],
  }
]);

async function loadResources() {
  try {
    const data = await api.getResources(portalStore.locale);
    if (data?.resources && data.resources.length > 0) {
      resources.value = data.resources;
    }
  } catch {
    // Keep defaults
  }
}

const filteredResources = computed(() => {
  return resources.value.filter(r => {
    const matchesFilter = selectedFilter.value === 'all' ||
      (r.filters && r.filters.some(f => f.slug === selectedFilter.value));
    const matchesQuery = r.title.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      (r.description && r.description.toLowerCase().includes(searchQuery.value.toLowerCase()));
    return matchesFilter && matchesQuery;
  });
});

function formatFileSize(bytes: number) {
  return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
}

function handleDownload(res: ResourceItem) {
  res.download_count++;
  alert(`Starting download for: ${res.title}`);
}

onMounted(() => {
  loadResources();
});

watch(() => portalStore.locale, () => {
  loadResources();
});
</script>

<template>
  <div class="resources-portal container section-spacing space-y-10">
    <div class="max-w-3xl space-y-3">
      <span class="badge badge-emerald">{{ portalStore.t.resourcesTitle }}</span>
      <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 dark:text-white">
        {{ portalStore.t.resourcesTitleFull }}
      </h1>
      <p class="text-sm md:text-base text-slate-500 dark:text-slate-400">
        {{ portalStore.t.resourcesSubtitleFull }}
      </p>
    </div>

    <!-- Filter and Search Bar -->
    <div class="civic-card p-4 flex flex-col md:flex-row items-center justify-between gap-4">
      <div class="flex items-center gap-2 overflow-x-auto w-full md:w-auto">
        <button
          v-for="filter in availableFilters"
          :key="filter.slug"
          @click="selectedFilter = filter.slug"
          class="px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap"
          :class="selectedFilter === filter.slug
            ? 'bg-emerald-600 text-white shadow'
            : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200'"
        >
          {{ filter.name }}
        </button>
      </div>

      <div class="relative w-full md:w-72">
        <UiIcon name="search" :size="16" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" />
        <input
          v-model="searchQuery"
          type="text"
          :placeholder="portalStore.t.filterKeywords"
          class="w-full pl-9 pr-4 py-2 text-xs bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:border-emerald-500"
        />
      </div>
    </div>

    <!-- Documents List -->
    <div class="space-y-4">
      <div
        v-for="res in filteredResources"
        :key="res.id"
        class="civic-card p-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-6"
      >
        <div class="flex items-start gap-4">
          <div class="w-14 h-14 rounded-2xl bg-red-100 dark:bg-red-950/40 text-red-600 dark:text-red-400 flex flex-col items-center justify-center shrink-0 border border-red-200 dark:border-red-900/40 font-bold">
            <UiIcon name="file-text" :size="20" />
            <span class="text-[10px] uppercase">{{ res.file_type }}</span>
          </div>

          <div class="space-y-1 max-w-2xl">
            <div class="flex items-center gap-2">
              <span
                v-for="f in res.filters"
                :key="f.slug"
                class="badge badge-emerald text-[10px]"
              >
                {{ f.name }}
              </span>
              <span class="text-xs text-slate-400">{{ formatFileSize(res.file_size) }}</span>
              <span class="text-xs text-slate-400">• {{ res.download_count }} downloads</span>
            </div>
            <h3 class="font-bold text-base md:text-lg text-slate-900 dark:text-white leading-snug">
              {{ res.title }}
            </h3>
            <p v-if="res.description" class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
              {{ res.description }}
            </p>
          </div>
        </div>

        <button
          @click="handleDownload(res)"
          class="btn btn-secondary text-xs px-5 py-2.5 rounded-xl flex items-center gap-2 hover:border-emerald-500 hover:text-emerald-600 shrink-0 w-full md:w-auto"
        >
          <UiIcon name="download" :size="16" />
          <span>{{ portalStore.t.downloadPdf }}</span>
        </button>
      </div>
    </div>
  </div>
</template>
