<script setup lang="ts">
import { ref, onMounted, watch } from 'vue';
import { usePortalStore } from '../stores/portal';
import { api } from '../services/api';
import UiIcon from '../components/UiIcon.vue';
import type { ExploreCategoryItem, ExploreDestination } from '../types';

const portalStore = usePortalStore();

const categories = ref<ExploreCategoryItem[]>([
  {
    id: 1,
    slug: 'parks-and-nature',
    name: 'Parks & Natural Sanctuaries',
    description: 'Breathtaking high-altitude pine forests, lush botanical promenades, and scenic viewpoints.',
    items: [
      {
        id: 1,
        slug: 'unity-park-palace-gardens',
        featured_image: 'https://images.unsplash.com/photo-1584824486509-112e4181ff6b?auto=format&fit=crop&w=800&q=80',
        name: 'Unity Park & Palace Gardens',
        description: 'Set inside the historic Grand Imperial Palace compound with royal pavilions, botanical collections, and zoo.',
      },
      {
        id: 2,
        slug: 'entoto-natural-panoramic-park',
        featured_image: 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=800&q=80',
        name: 'Entoto Natural Panoramic Park',
        description: 'Eucalyptus forest trails, horse riding, canopy walkways, and 360-degree views of the capital.',
      }
    ]
  },
  {
    id: 2,
    slug: 'heritage-and-museums',
    name: 'Heritage, Culture & Museums',
    description: 'Centuries of ecclesiastical art, hominid archaeology, and imperial crowns.',
    items: [
      {
        id: 3,
        slug: 'national-museum-lucy-dinkinesh',
        featured_image: 'https://images.unsplash.com/photo-1566127444979-b3d2b654e3d7?auto=format&fit=crop&w=800&q=80',
        name: 'National Museum of Ethiopia (Lucy / Dinkinesh)',
        description: 'Discover the world-famous 3.2-million-year-old skeleton of Lucy (Dinkinesh) and ancient Axumite relics.',
      }
    ]
  }
]);

const selectedDestination = ref<ExploreDestination | null>(null);

async function loadExploreData() {
  try {
    const data = await api.getExplore(portalStore.locale);
    if (data && data.length > 0) {
      categories.value = data;
    }
  } catch {
    // Keep defaults
  }
}

onMounted(() => {
  loadExploreData();
});

watch(() => portalStore.locale, () => {
  loadExploreData();
});
</script>

<template>
  <div class="explore-portal container section-spacing space-y-12">
    <!-- Header -->
    <div class="max-w-3xl space-y-3">
      <span class="badge badge-emerald">{{ portalStore.t.exploreCity }}</span>
      <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 dark:text-white">
        {{ portalStore.t.exploreCity }}
      </h1>
      <p class="text-sm md:text-base text-slate-500 dark:text-slate-400">
        {{ portalStore.t.exploreSubtitle }}
      </p>
    </div>

    <!-- Category Sections -->
    <div v-for="cat in categories" :key="cat.id" class="space-y-6">
      <div class="border-b border-slate-200 dark:border-slate-800 pb-3 flex items-center justify-between">
        <div>
          <h2 class="text-xl font-bold text-slate-900 dark:text-white">{{ cat.name }}</h2>
          <p class="text-xs text-slate-500">{{ cat.description }}</p>
        </div>
        <span class="badge badge-gold">{{ cat.items?.length || 0 }} {{ portalStore.t.topSanctuary }}</span>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="item in cat.items"
          :key="item.id"
          class="civic-card group overflow-hidden cursor-pointer"
          @click="selectedDestination = item"
        >
          <div class="h-56 overflow-hidden relative">
            <img
              :src="item.featured_image"
              :alt="item.name"
              class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
            />
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/30 to-transparent"></div>
            <div class="absolute bottom-4 left-4 right-4 text-white">
              <h3 class="font-bold text-base text-white leading-tight mb-1">{{ item.name }}</h3>
              <p class="text-xs text-slate-300 line-clamp-2 leading-relaxed">{{ item.description }}</p>
            </div>
          </div>
          <div class="p-4 bg-white dark:bg-slate-900 flex items-center justify-between text-xs">
            <span class="font-bold text-emerald-600 dark:text-emerald-400 flex items-center gap-1">
              Explore Guide <UiIcon name="arrow-right" :size="12" />
            </span>
            <span class="text-slate-400 text-[11px]">Visitor Info & Hours</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Destination Modal -->
    <transition name="modal-fade">
      <div
        v-if="selectedDestination"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-md"
        @click.self="selectedDestination = null"
      >
        <div class="w-full max-w-2xl bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-800 p-6 md:p-8 space-y-4">
          <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-3">
            <span class="badge badge-emerald">Visitor Guide</span>
            <button @click="selectedDestination = null" class="p-1 text-slate-400 hover:text-slate-600">
              <UiIcon name="x" :size="18" />
            </button>
          </div>
          <div class="h-64 rounded-xl overflow-hidden">
            <img :src="selectedDestination.featured_image" :alt="selectedDestination.name" class="w-full h-full object-cover" />
          </div>
          <h2 class="text-2xl font-bold text-slate-900 dark:text-white">{{ selectedDestination.name }}</h2>
          <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed">{{ selectedDestination.description }}</p>
          <div class="pt-4 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between text-xs text-slate-500">
            <span><strong>Visiting Hours:</strong> Tuesday - Sunday: 9:00 AM - 6:00 PM</span>
            <button class="btn btn-primary text-xs px-4 py-2" @click="selectedDestination = null">Done</button>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>
