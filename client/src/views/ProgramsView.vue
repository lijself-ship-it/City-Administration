<script setup lang="ts">
import { ref, onMounted, watch } from 'vue';
import { usePortalStore } from '../stores/portal';
import { api } from '../services/api';
import type { ProgramItem } from '../types';

const portalStore = usePortalStore();

const programs = ref<ProgramItem[]>([
  {
    id: 1,
    slug: 'corridor-development-and-urban-renewal',
    featured_image: 'https://images.unsplash.com/photo-1477959858617-67f30bc75b82?auto=format&fit=crop&w=800&q=80',
    title: 'Corridor Development & Urban Renewal Masterplan',
    description: 'Comprehensive multi-year program revitalizing arterial avenues with wide shaded sidewalks, underground utility ducts, dedicated bus lanes, and modern public plazas.',
    objectives: '1. Complete 48km of green corridors.\n2. Plant 500,000 indigenous trees along roads.\n3. Modernize bus stops and street lighting.',
    progress_percentage: 74,
    budget: 250000000,
    status: 'ongoing',
  },
  {
    id: 2,
    slug: 'smart-city-and-digital-inclusion',
    featured_image: 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=800&q=80',
    title: 'Smart City Infrastructure & Digital Inclusion',
    description: 'Expanding municipal metropolitan fiber optic backbone, free public Wi-Fi hubs in community libraries, and paperless administrative workflows.',
    objectives: '1. Lay 1,200km fiber optics.\n2. Digitally automate 100% of municipal permits.\n3. Train 20,000 youth in digital literacy.',
    progress_percentage: 58,
    budget: 120000000,
    status: 'ongoing',
  }
]);

async function loadPrograms() {
  try {
    const data = await api.getPrograms(portalStore.locale);
    if (data && data.length > 0) programs.value = data;
  } catch {
    // Keep defaults
  }
}

onMounted(() => {
  loadPrograms();
});

watch(() => portalStore.locale, () => {
  loadPrograms();
});
</script>

<template>
  <div class="programs-portal container section-spacing space-y-10">
    <div class="max-w-3xl space-y-3">
      <span class="badge badge-emerald">{{ portalStore.t.megaProjects }}</span>
      <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 dark:text-white">
        {{ portalStore.t.programsTitle }}
      </h1>
      <p class="text-sm md:text-base text-slate-500 dark:text-slate-400">
        {{ portalStore.t.programsSubtitle }}
      </p>
    </div>

    <div class="space-y-8">
      <div
        v-for="program in programs"
        :key="program.id"
        class="civic-card p-6 md:p-8 grid grid-cols-1 md:grid-cols-3 gap-8 items-center"
      >
        <div class="h-60 rounded-2xl overflow-hidden shadow-lg border border-slate-200 dark:border-slate-800">
          <img :src="program.featured_image" :alt="program.title" class="w-full h-full object-cover" />
        </div>

        <div class="md:col-span-2 space-y-4">
          <div class="flex items-center gap-3">
            <span class="badge badge-emerald uppercase font-bold">{{ program.status }}</span>
            <span class="text-xs text-slate-400">{{ portalStore.t.allocatedBudget }}: ETB {{ (program.budget || 0).toLocaleString() }}</span>
          </div>

          <h2 class="text-xl md:text-2xl font-bold text-slate-900 dark:text-white leading-tight">
            {{ program.title }}
          </h2>

          <p class="text-xs md:text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
            {{ program.description }}
          </p>

          <!-- Progress Bar -->
          <div class="space-y-1.5 pt-2">
            <div class="flex items-center justify-between text-xs font-bold">
              <span class="text-slate-700 dark:text-slate-300">{{ portalStore.t.overallProgress }}</span>
              <span class="text-emerald-600 dark:text-emerald-400">{{ program.progress_percentage }}%</span>
            </div>
            <div class="w-full h-2.5 rounded-full bg-slate-100 dark:bg-slate-800 overflow-hidden">
              <div
                class="h-full bg-gradient-to-r from-emerald-600 to-emerald-400 rounded-full transition-all duration-1000"
                :style="{ width: `${program.progress_percentage}%` }"
              ></div>
            </div>
          </div>

          <div v-if="program.objectives" class="pt-2 text-xs text-slate-500 whitespace-pre-line">
            <strong>{{ portalStore.t.projectObjectives }}:</strong><br />
            {{ program.objectives }}
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
