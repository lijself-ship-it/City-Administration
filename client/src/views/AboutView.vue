<script setup lang="ts">
import { usePortalStore } from '../stores/portal';
import UiIcon from '../components/UiIcon.vue';

const portalStore = usePortalStore();
</script>

<template>
  <div class="about-portal container section-spacing space-y-12">
    <!-- Header Hero -->
    <div class="relative rounded-3xl overflow-hidden bg-slate-900 text-white p-8 md:p-14 shadow-2xl">
      <div class="absolute inset-0 opacity-30">
        <img
          src="https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&w=1200&q=80"
          alt="Kombolcha Hub"
          class="w-full h-full object-cover"
        />
      </div>
      <div class="relative z-10 max-w-3xl space-y-4">
        <span class="badge badge-emerald">{{ portalStore.t.portalTagline }}</span>
        <h1 class="text-3xl md:text-5xl font-extrabold tracking-tight">
          {{ portalStore.t.aboutCity }}
        </h1>
        <p class="text-sm md:text-lg text-slate-200 leading-relaxed">
          {{ portalStore.t.aboutIntro }}
        </p>
      </div>
    </div>

    <!-- Pillars Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      <div class="civic-card p-6 space-y-3">
        <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
          <UiIcon name="landmark" :size="24" />
        </div>
        <h3 class="font-bold text-lg text-slate-900 dark:text-white">{{ portalStore.t.industrialPillar }}</h3>
        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
          {{ portalStore.t.industrialPillarDesc }}
        </p>
      </div>

      <div class="civic-card p-6 space-y-3">
        <div class="w-12 h-12 rounded-xl bg-amber-100 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 flex items-center justify-center">
          <UiIcon name="compass" :size="24" />
        </div>
        <h3 class="font-bold text-lg text-slate-900 dark:text-white">{{ portalStore.t.logisticsPillar }}</h3>
        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
          {{ portalStore.t.logisticsPillarDesc }}
        </p>
      </div>

      <div class="civic-card p-6 space-y-3">
        <div class="w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 flex items-center justify-center">
          <UiIcon name="layers" :size="24" />
        </div>
        <h3 class="font-bold text-lg text-slate-900 dark:text-white">{{ portalStore.t.culturalPillar }}</h3>
        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
          {{ portalStore.t.culturalPillarDesc }}
        </p>
      </div>
    </div>

    <!-- City Charter & Fast Facts -->
    <div class="civic-card p-8 grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
      <div class="space-y-4">
        <span class="badge badge-gold">{{ portalStore.t.fastFacts }}</span>
        <h3 class="text-2xl font-bold text-slate-900 dark:text-white">{{ portalStore.t.vitalStats }}</h3>
        <ul class="space-y-3 text-xs md:text-sm text-slate-600 dark:text-slate-300">
          <li class="flex items-center gap-3">
            <UiIcon name="check" :size="16" class="text-emerald-500" />
            <span><strong>{{ portalStore.t.elevation }}:</strong> {{ portalStore.stats.elevation }}</span>
          </li>
          <li class="flex items-center gap-3">
            <UiIcon name="check" :size="16" class="text-emerald-500" />
            <span><strong>{{ portalStore.t.population }}:</strong> {{ portalStore.stats.population }}</span>
          </li>
          <li class="flex items-center gap-3">
            <UiIcon name="check" :size="16" class="text-emerald-500" />
            <span><strong>{{ portalStore.t.industrialPillar }}:</strong> 75 Ha IPDC Eco-Park</span>
          </li>
          <li class="flex items-center gap-3">
            <UiIcon name="check" :size="16" class="text-emerald-500" />
            <span><strong>{{ portalStore.t.activeServices }}:</strong> {{ portalStore.stats.services_count }}+ Digitized Civic Portals</span>
          </li>
        </ul>
      </div>

      <div class="rounded-2xl overflow-hidden shadow-xl border border-slate-200 dark:border-slate-800 h-64">
        <img
          src="https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&w=800&q=80"
          alt="Kombolcha Cityscape"
          class="w-full h-full object-cover"
        />
      </div>
    </div>
  </div>
</template>
