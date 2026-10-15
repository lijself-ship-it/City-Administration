<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue';
import { usePortalStore } from '../stores/portal';
import { api } from '../services/api';
import UiIcon from '../components/UiIcon.vue';
import type { GovernmentMemberItem, GovernmentDepartmentItem } from '../types';

const portalStore = usePortalStore();

const activeTab = ref<'mayor' | 'cabinet' | 'council' | 'structure' | 'sub-cities'>('mayor');

const members = ref<GovernmentMemberItem[]>([
  {
    id: 1,
    type: 'mayor',
    photo: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=600&q=80',
    name: 'Adanech Abiebie',
    title: 'Mayor of the City Administration',
    email: 'mayor@city.gov.et',
    phone: '+251 11 123 4567',
    bio: 'Committed to delivering inclusive urban prosperity, clean green corridors, and digitized citizen services that empower all communities.',
  },
  {
    id: 2,
    type: 'cabinet',
    photo: 'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=600&q=80',
    name: 'Dr. Solomon Bekele',
    title: 'Head of Innovation & Smart City Bureau',
    email: 'cabinet.innovation@city.gov.et',
    phone: '+251 11 234 5678',
    bio: 'Leading the municipal digital transformation, fiber connectivity, and e-government automation across all 11 sub-cities.',
  },
  {
    id: 3,
    type: 'cabinet',
    photo: 'https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=600&q=80',
    name: 'Eng. Bethlehem Tadesse',
    title: 'Head of Mega Projects & Infrastructure',
    email: 'cabinet.infrastructure@city.gov.et',
    phone: '+251 11 345 6789',
    bio: 'Overseeing highway corridors, public transit networks, river revamping, and pedestrian walkways.',
  },
  {
    id: 4,
    type: 'council',
    photo: 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&w=600&q=80',
    name: 'Hon. Tesfaye Dibaba',
    title: 'Speaker of the City Council',
    email: 'council.speaker@city.gov.et',
    phone: '+251 11 456 7890',
    bio: 'Presiding over legislative policy, citizen representation, public hearings, and municipal oversight.',
  }
]);

const departments = ref<GovernmentDepartmentItem[]>([
  {
    id: 1,
    type: 'structure',
    name: 'Executive Office of the Mayor',
    description: 'Central policy coordination, cabinet oversight, and strategic city leadership.',
    address: 'City Hall, Churchill Avenue',
    phone: '+251 11 551 0000',
    email: 'info@city.gov.et',
  },
  {
    id: 2,
    type: 'sub_city',
    name: 'Bole Sub-City Administration',
    description: 'Metropolitan hub encompassing Bole International Airport, business district, and residential woredas.',
    address: 'Bole Medhanialem Square',
    phone: '+251 11 661 1111',
    email: 'bole@city.gov.et',
  },
  {
    id: 3,
    type: 'sub_city',
    name: 'Kirkos Sub-City Administration',
    description: 'Diplomatic enclave hosting the African Union headquarters, UNECA, and financial avenue.',
    address: 'Meskel Square / Mexico District',
    phone: '+251 11 442 2222',
    email: 'kirkos@city.gov.et',
  },
  {
    id: 4,
    type: 'sub_city',
    name: 'Arada Sub-City Administration',
    description: 'Historic civic heart featuring Piazza, City Hall, and premier cultural institutions.',
    address: 'Piazza Heritage Zone',
    phone: '+251 11 112 3333',
    email: 'arada@city.gov.et',
  }
]);

async function loadGovData() {
  try {
    const mem = await api.getGovernmentMembers(portalStore.locale);
    if (mem && mem.length > 0) members.value = mem;

    const dept = await api.getGovernmentDepartments(portalStore.locale);
    if (dept && dept.length > 0) departments.value = dept;
  } catch {
    // Keep defaults
  }
}

onMounted(() => {
  const hash = window.location.hash.replace('#', '');
  if (['mayor', 'cabinet', 'council', 'structure', 'sub-cities'].includes(hash)) {
    activeTab.value = hash as any;
  }
  loadGovData();
});

watch(() => portalStore.locale, () => {
  loadGovData();
});

const govTabs = computed(() => [
  { key: 'mayor', label: portalStore.t.mayorsOffice, icon: 'user' },
  { key: 'cabinet', label: portalStore.t.executiveCabinet, icon: 'briefcase' },
  { key: 'council', label: portalStore.t.cityCouncil, icon: 'landmark' },
  { key: 'sub-cities', label: portalStore.t.kebelesBranches, icon: 'building' },
]);
</script>

<template>
  <div class="government-portal container section-spacing space-y-10">
    <!-- Header -->
    <div class="max-w-3xl space-y-2">
      <span class="badge badge-emerald">{{ portalStore.t.administration }}</span>
      <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 dark:text-white">
        {{ portalStore.t.govTitle }}
      </h1>
      <p class="text-sm md:text-base text-slate-500 dark:text-slate-400">
        {{ portalStore.t.govSubtitle }}
      </p>
    </div>

    <!-- Navigation Tabs -->
    <div class="flex items-center gap-2 overflow-x-auto pb-2 border-b border-slate-200 dark:border-slate-800">
      <button
        v-for="tab in govTabs"
        :key="tab.key"
        @click="activeTab = tab.key as any"
        class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs md:text-sm font-bold whitespace-nowrap transition"
        :class="activeTab === tab.key
          ? 'bg-emerald-600 text-white shadow-md'
          : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700'"
      >
        <UiIcon :name="tab.icon" :size="16" />
        {{ tab.label }}
      </button>
    </div>

    <!-- Tab 1: Mayor's Office -->
    <div v-if="activeTab === 'mayor'" class="space-y-8 animate-fade-in">
      <div class="civic-card p-6 md:p-10 grid grid-cols-1 md:grid-cols-3 gap-8 items-center">
        <div class="flex flex-col items-center text-center">
          <div class="w-48 h-48 rounded-2xl overflow-hidden shadow-2xl border-4 border-emerald-500/30 mb-4">
            <img
              src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=600&q=80"
              alt="Mayor Portrait"
              class="w-full h-full object-cover"
            />
          </div>
          <h2 class="font-extrabold text-lg text-slate-900 dark:text-white">{{ portalStore.t.mayorName }}</h2>
          <p class="text-xs text-emerald-600 dark:text-emerald-400 font-semibold">{{ portalStore.t.mayorTitle }}</p>
          <div class="flex items-center gap-3 mt-3 text-xs text-slate-500">
            <a href="mailto:mayor@city.gov.et" class="hover:text-emerald-600 flex items-center gap-1">
              <UiIcon name="mail" :size="14" />
              {{ portalStore.t.contactOfficer }}
            </a>
          </div>
        </div>

        <div class="md:col-span-2 space-y-4">
          <span class="badge badge-emerald">{{ portalStore.t.executiveMandate }}</span>
          <h3 class="text-xl md:text-2xl font-bold text-slate-900 dark:text-white">
            {{ portalStore.t.mayorQuote }}
          </h3>
          <p class="text-xs md:text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
            {{ portalStore.t.executiveMandateDesc }}
          </p>
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-4">
            <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700/60 text-center">
              <div class="text-xl font-bold text-emerald-600 dark:text-emerald-400">4</div>
              <div class="text-[11px] text-slate-500 font-medium">{{ portalStore.t.subCityAdmins }}</div>
            </div>
            <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700/60 text-center">
              <div class="text-xl font-bold text-amber-600 dark:text-amber-400">12</div>
              <div class="text-[11px] text-slate-500 font-medium">{{ portalStore.t.woredaCenters }}</div>
            </div>
            <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700/60 text-center">
              <div class="text-xl font-bold text-blue-600 dark:text-blue-400">{{ portalStore.stats.services_count }}+</div>
              <div class="text-[11px] text-slate-500 font-medium">{{ portalStore.t.digitalPublicServices }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Tab 2: Cabinet Members -->
    <div v-else-if="activeTab === 'cabinet'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 animate-fade-in">
      <div
        v-for="member in members.filter(m => m.type === 'cabinet')"
        :key="member.id"
        class="civic-card p-6 space-y-4"
      >
        <div class="flex items-center gap-4">
          <div class="w-16 h-16 rounded-xl overflow-hidden shrink-0 border-2 border-emerald-500/20 shadow">
            <img :src="member.photo" :alt="member.name" class="w-full h-full object-cover" />
          </div>
          <div>
            <h3 class="font-bold text-sm text-slate-900 dark:text-white">{{ member.name }}</h3>
            <p class="text-xs text-emerald-600 dark:text-emerald-400 font-medium leading-snug">{{ member.title }}</p>
          </div>
        </div>
        <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-3 leading-relaxed">
          {{ member.bio }}
        </p>
        <div class="pt-3 border-t border-slate-100 dark:border-slate-800 text-xs text-slate-400 flex items-center justify-between">
          <span class="flex items-center gap-1"><UiIcon name="phone" :size="12" /> {{ member.phone }}</span>
          <span class="flex items-center gap-1"><UiIcon name="mail" :size="12" /> Official</span>
        </div>
      </div>
    </div>

    <!-- Tab 3: City Council -->
    <div v-else-if="activeTab === 'council'" class="space-y-6 animate-fade-in">
      <div class="civic-card p-6 md:p-8 space-y-4 bg-gradient-to-r from-blue-900/10 to-transparent">
        <span class="badge badge-blue">{{ portalStore.t.legislativeBody }}</span>
        <h3 class="text-xl font-bold text-slate-900 dark:text-white">
          {{ portalStore.t.cityCouncil }}
        </h3>
        <p class="text-xs md:text-sm text-slate-600 dark:text-slate-300 leading-relaxed max-w-3xl">
          {{ portalStore.t.cityCouncilDesc }}
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div
          v-for="member in members.filter(m => m.type === 'council')"
          :key="member.id"
          class="civic-card p-6 flex gap-4 items-center"
        >
          <div class="w-20 h-20 rounded-2xl overflow-hidden shrink-0 border-2 border-blue-500/30 shadow">
            <img :src="member.photo" :alt="member.name" class="w-full h-full object-cover" />
          </div>
          <div class="space-y-1">
            <h4 class="font-bold text-base text-slate-900 dark:text-white">{{ member.name }}</h4>
            <p class="text-xs text-blue-600 dark:text-blue-400 font-semibold">{{ member.title }}</p>
            <p class="text-xs text-slate-500">{{ member.bio }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Tab 4: Sub-Cities & Offices -->
    <div v-else class="space-y-6 animate-fade-in">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div
          v-for="dept in departments"
          :key="dept.id"
          class="civic-card p-6 space-y-3"
        >
          <div class="flex items-center justify-between">
            <span class="badge" :class="dept.type === 'sub_city' ? 'badge-emerald' : 'badge-gold'">
              {{ dept.type === 'sub_city' ? portalStore.t.kebelesBranches : portalStore.t.administration }}
            </span>
            <span class="text-xs text-slate-400 font-mono">{{ dept.phone }}</span>
          </div>

          <h3 class="font-bold text-base text-slate-900 dark:text-white">{{ dept.name }}</h3>
          <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">{{ dept.description }}</p>

          <div class="pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs text-slate-500">
            <span class="flex items-center gap-1.5"><UiIcon name="map-pin" :size="13" class="text-emerald-600" /> {{ dept.address }}</span>
            <span class="text-emerald-600 font-bold hover:underline cursor-pointer">{{ portalStore.t.directory }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
