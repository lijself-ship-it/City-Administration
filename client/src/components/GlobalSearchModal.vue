<script setup lang="ts">
import { ref, watch, nextTick } from 'vue';
import { useRouter } from 'vue-router';
import { usePortalStore } from '../stores/portal';
import { api } from '../services/api';
import type { SearchResultItem } from '../types';
import UiIcon from './UiIcon.vue';

const router = useRouter();
const portalStore = usePortalStore();

const query = ref('');
const results = ref<SearchResultItem[]>([]);
const isSearching = ref(false);
const inputRef = ref<HTMLInputElement | null>(null);

// Fallback search index for Kombolcha municipal records
const fallbackIndex: SearchResultItem[] = [
  { id: 1, type: 'Service', title: 'Building & Construction Permit Application', snippet: 'Complete digital clearance, engineering review, and online payment for Kombolcha.', url: '/services#building-construction-permit' },
  { id: 2, type: 'Service', title: 'Vital Events & Civil Registration', snippet: 'Official birth certificate and marriage registration within 24 to 48 hours.', url: '/services#vital-events-birth-registration' },
  { id: 3, type: 'Service', title: 'Commercial Trade & Industrial License Renewal', snippet: 'Annual trade licensing for manufacturing enterprises and retail operations.', url: '/services#commercial-trade-license-renewal' },
  { id: 4, type: 'News', title: 'Kombolcha Industrial Park Welcomes New Manufacturers', snippet: 'Over 4,500 new industrial jobs created as production sheds reach full operational capacity.', url: '/media?tab=news' },
  { id: 5, type: 'Tender', title: 'Public Infrastructure & Urban Corridor Construction', snippet: 'Kombolcha municipal procurement and open engineering bids.', url: '/media?tab=tenders' },
  { id: 6, type: 'Government', title: 'Kebele 05 Administration (Mitikollo / Industrial Zone)', snippet: 'Industrial park corridor, dry port terminal, and public administration.', url: '/government#sub-cities' },
  { id: 7, type: 'Explore', title: 'Kombolcha Dry Port & Multimodal Logistics Terminal', snippet: 'Key inland freight customs terminal connecting port corridors.', url: '/explore' },
];

let debounceTimer: any = null;

watch(() => portalStore.isSearchOpen, (isOpen) => {
  if (isOpen) {
    nextTick(() => {
      inputRef.value?.focus();
    });
  } else {
    query.value = '';
    results.value = [];
  }
});

watch(query, (val) => {
  clearTimeout(debounceTimer);
  if (!val.trim() || val.length < 2) {
    results.value = [];
    return;
  }

  isSearching.value = true;
  debounceTimer = setTimeout(async () => {
    try {
      const liveResults = await api.search(val, portalStore.locale);
      if (liveResults && liveResults.length > 0) {
        results.value = liveResults;
      } else {
        // Search local fallback
        const q = val.toLowerCase();
        results.value = fallbackIndex.filter(item =>
          item.title.toLowerCase().includes(q) || item.snippet.toLowerCase().includes(q)
        );
      }
    } catch {
      const q = val.toLowerCase();
      results.value = fallbackIndex.filter(item =>
        item.title.toLowerCase().includes(q) || item.snippet.toLowerCase().includes(q)
      );
    } finally {
      isSearching.value = false;
    }
  }, 200);
});

function handleNavigate(url: string) {
  portalStore.closeSearch();
  router.push(url);
}
</script>

<template>
  <transition name="modal-fade">
    <div
      v-if="portalStore.isSearchOpen"
      class="fixed inset-0 z-50 flex items-start justify-center pt-20 px-4 bg-slate-950/70 backdrop-blur-md"
      @click.self="portalStore.closeSearch()"
    >
      <div class="w-full max-w-2xl bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-800 overflow-hidden transform transition-all">
        <!-- Input Header -->
        <div class="flex items-center gap-3 px-4 py-3.5 border-b border-slate-200 dark:border-slate-800">
          <UiIcon name="search" :size="20" class="text-slate-400" />
          <input
            ref="inputRef"
            v-model="query"
            type="text"
            :placeholder="portalStore.t.searchPlaceholder"
            class="w-full text-base bg-transparent text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none"
          />
          <button
            @click="portalStore.closeSearch()"
            class="p-1 rounded-md text-slate-400 hover:text-slate-600 dark:hover:text-slate-200"
          >
            <kbd class="px-2 py-0.5 text-xs bg-slate-100 dark:bg-slate-800 rounded font-mono">ESC</kbd>
          </button>
        </div>

        <!-- Quick Filters / Results Body -->
        <div class="max-h-96 overflow-y-auto p-4 space-y-2">
          <div v-if="isSearching" class="text-center py-8 text-sm text-slate-400">
            {{ portalStore.t.searchCityDatabases }}
          </div>

          <div v-else-if="results.length > 0" class="space-y-1.5">
            <div
              v-for="item in results"
              :key="item.id"
              @click="handleNavigate(item.url)"
              class="p-3 rounded-xl hover:bg-emerald-50 dark:hover:bg-emerald-950/30 cursor-pointer border border-transparent hover:border-emerald-200 dark:hover:border-emerald-900/40 transition group"
            >
              <div class="flex items-center justify-between mb-1">
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400 bg-emerald-100/60 dark:bg-emerald-900/40 px-2 py-0.5 rounded">
                  {{ item.type }}
                </span>
                <UiIcon name="arrow-right" :size="14" class="text-slate-300 group-hover:text-emerald-600 transition" />
              </div>
              <h4 class="font-bold text-sm text-slate-800 dark:text-slate-100 group-hover:text-emerald-600 transition">
                {{ item.title }}
              </h4>
              <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-1 mt-0.5">
                {{ item.snippet }}
              </p>
            </div>
          </div>

          <!-- Empty / Suggestions state -->
          <div v-else class="text-center py-8 space-y-3">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
              {{ portalStore.t.popularSearches }}
            </p>
            <div class="flex flex-wrap justify-center gap-2">
              <button
                v-for="s in ['Building Permit', 'Birth Certificate', 'Corridor Phase 2', 'Tenders', 'Sub-City Offices']"
                :key="s"
                @click="query = s"
                class="px-3 py-1 text-xs rounded-full bg-slate-100 dark:bg-slate-800 hover:bg-emerald-100 dark:hover:bg-emerald-900/40 hover:text-emerald-600 transition text-slate-600 dark:text-slate-300"
              >
                {{ s }}
              </button>
            </div>
          </div>
        </div>

        <!-- Footer Shortcuts -->
        <div class="bg-slate-50 dark:bg-slate-950 px-4 py-2 border-t border-slate-200 dark:border-slate-800 text-[11px] text-slate-400 flex items-center justify-between">
          <span>{{ portalStore.t.navigateKeyboard }}</span>
          <span>{{ portalStore.t.pressEscToExit }}</span>
        </div>
      </div>
    </div>
  </transition>
</template>

<style scoped>
.modal-fade-enter-active,
.modal-fade-leave-active {
  transition: opacity 0.2s ease;
}
.modal-fade-enter-from,
.modal-fade-leave-to {
  opacity: 0;
}
</style>
