<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue';
import { usePortalStore } from '../stores/portal';
import { api } from '../services/api';
import UiIcon from '../components/UiIcon.vue';
import type { ServiceItem } from '../types';

const portalStore = usePortalStore();

const searchQuery = ref('');
const selectedType = ref<'all' | 'step' | 'text'>('all');
const activeService = ref<ServiceItem | null>(null);
const currentStep = ref<number>(1);
const applicationSubmitted = ref(false);

const services = ref<ServiceItem[]>([
  {
    id: 1,
    slug: 'building-construction-permit',
    type: 'step',
    icon: 'home',
    featured_image: 'https://images.unsplash.com/photo-1541888946425-d0fbb186156f?auto=format&fit=crop&w=800&q=80',
    title: 'Building & Construction Permit Application',
    description: 'Complete digital clearance for architectural drawings, structural safety, environmental clearance, and QR-code certified permit issuance.',
    steps_count: 4,
    steps: [
      { id: 101, step_number: 1, icon: 'file-text', title: 'Document Verification & Land Title', description: 'Submit certified digital copy of land title deed, site plan, and tax clearance certificate.' },
      { id: 102, step_number: 2, icon: 'check-circle', title: 'Engineering & Architectural Review', description: 'Municipal engineers review structural safety, fire safety compliance, and green area ratios.' },
      { id: 103, step_number: 3, icon: 'credit-card', title: 'Fee Assessment & Online Payment', description: 'Automated invoice generation and instant settlement via Telebirr, CBE Birr, or commercial bank.' },
      { id: 104, step_number: 4, icon: 'download', title: 'Issuance of Digital Construction Permit', description: 'Download QR-code verified official construction permit for physical display at site.' },
    ]
  },
  {
    id: 2,
    slug: 'vital-events-birth-registration',
    type: 'text',
    icon: 'user-check',
    featured_image: 'https://images.unsplash.com/photo-1576765608535-5f04d1e3f289?auto=format&fit=crop&w=800&q=80',
    title: 'Vital Events & Birth Certificate Issuance',
    description: 'Official registration of births within the city administration with legal certificate issuance within 48 hours. Parents can register newborns within 90 days of birth at any Woreda vital events bureau or through this digital portal.',
    steps_count: 0,
  },
  {
    id: 3,
    slug: 'commercial-trade-license-renewal',
    type: 'step',
    icon: 'briefcase',
    featured_image: 'https://images.unsplash.com/photo-1450133064473-71024230f91b?auto=format&fit=crop&w=800&q=80',
    title: 'Commercial Trade License Renewal',
    description: 'Annual business license renewal with automated tax clearance check and instant digital certificate for small, medium, and large enterprises.',
    steps_count: 3,
    steps: [
      { id: 201, step_number: 1, icon: 'file-text', title: 'TIN & Revenue Clearance Verification', description: 'Real-time automated validation with Ethiopian Ministry of Revenues.' },
      { id: 202, step_number: 2, icon: 'credit-card', title: 'Municipal Inspection Fee Settlement', description: 'Direct mobile settlement through Telebirr or electronic bank transfer.' },
      { id: 203, step_number: 3, icon: 'download', title: 'Instant Digital License Certificate', description: 'Download authenticated digital license with barcoded validation.' },
    ]
  }
]);

async function loadServices() {
  try {
    const data = await api.getServices(portalStore.locale);
    if (data && data.length > 0) {
      services.value = data;
    }
  } catch {
    // Keep defaults
  }
}

const filteredServices = computed(() => {
  return services.value.filter(s => {
    const matchesType = selectedType.value === 'all' || s.type === selectedType.value;
    const matchesQuery = s.title.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                         s.description.toLowerCase().includes(searchQuery.value.toLowerCase());
    return matchesType && matchesQuery;
  });
});

function openDetail(service: ServiceItem) {
  activeService.value = service;
  currentStep.value = 1;
  applicationSubmitted.value = false;
}

function closeDetail() {
  activeService.value = null;
}

onMounted(() => {
  loadServices();
  const hash = window.location.hash.replace('#', '');
  if (hash) {
    const match = services.value.find(s => s.slug === hash);
    if (match) openDetail(match);
  }
});

watch(() => portalStore.locale, () => {
  loadServices();
});
</script>

<template>
  <div class="services-portal container section-spacing space-y-10">
    <!-- Header & Search -->
    <div class="max-w-3xl space-y-3">
      <span class="badge badge-emerald">{{ portalStore.t.quickServices }}</span>
      <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 dark:text-white">
        {{ portalStore.t.servicesTitle }}
      </h1>
      <p class="text-sm md:text-base text-slate-500 dark:text-slate-400">
        {{ portalStore.t.servicesSubtitle }}
      </p>
    </div>

    <!-- Controls: Filter Pills & Search -->
    <div class="civic-card p-4 flex flex-col md:flex-row items-center justify-between gap-4">
      <div class="flex items-center gap-2 overflow-x-auto w-full md:w-auto">
        <button
          @click="selectedType = 'all'"
          class="px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap"
          :class="selectedType === 'all' ? 'bg-emerald-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300'"
        >
          {{ portalStore.t.allServices }}
        </button>
        <button
          @click="selectedType = 'step'"
          class="px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap"
          :class="selectedType === 'step' ? 'bg-emerald-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300'"
        >
          {{ portalStore.t.stepGuidesTab }}
        </button>
        <button
          @click="selectedType = 'text'"
          class="px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap"
          :class="selectedType === 'text' ? 'bg-emerald-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300'"
        >
          {{ portalStore.t.textServicesTab }}
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

    <!-- Services Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div
        v-for="service in filteredServices"
        :key="service.id"
        class="civic-card p-6 flex flex-col justify-between hover:border-emerald-500 transition cursor-pointer"
        @click="openDetail(service)"
      >
        <div class="space-y-4">
          <div class="flex items-center justify-between">
            <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
              <UiIcon :name="service.icon || 'layers'" :size="24" />
            </div>
            <span
              class="badge"
              :class="service.type === 'step' ? 'badge-blue' : 'badge-emerald'"
            >
              {{ service.type === 'step' ? `${service.steps_count || 4} ${portalStore.t.stepsGuide}` : portalStore.t.instantService }}
            </span>
          </div>

          <h3 class="font-bold text-base text-slate-900 dark:text-white leading-snug">
            {{ service.title }}
          </h3>

          <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-3 leading-relaxed">
            {{ service.description }}
          </p>
        </div>

        <div class="pt-6 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs">
          <span class="font-bold text-emerald-600 dark:text-emerald-400 flex items-center gap-1">
            <span>{{ service.type === 'step' ? portalStore.t.launchGuide : portalStore.t.readRequirements }}</span>
            <UiIcon name="arrow-right" :size="12" />
          </span>
          <span class="text-slate-400 text-[11px]">{{ portalStore.t.freeOnlineAccess }}</span>
        </div>
      </div>
    </div>

    <!-- Service Detail & Interactive Step Guide Modal -->
    <transition name="modal-fade">
      <div
        v-if="activeService"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-md"
        @click.self="closeDetail"
      >
        <div class="w-full max-w-3xl max-h-[90vh] overflow-y-auto bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-800 p-6 md:p-8 space-y-6">
          <!-- Modal Header -->
          <div class="flex items-start justify-between border-b border-slate-200 dark:border-slate-800 pb-4">
            <div class="space-y-1">
              <span class="badge" :class="activeService.type === 'step' ? 'badge-blue' : 'badge-emerald'">
                {{ activeService.type === 'step' ? portalStore.t.stepGuidesTab : portalStore.t.textServicesTab }}
              </span>
              <h2 class="text-xl md:text-2xl font-bold text-slate-900 dark:text-white">
                {{ activeService.title }}
              </h2>
            </div>
            <button @click="closeDetail" class="p-1 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
              <UiIcon name="x" :size="20" />
            </button>
          </div>

          <!-- Description -->
          <p class="text-xs md:text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
            {{ activeService.description }}
          </p>

          <!-- Step-by-Step Walkthrough Section (For Step Services) -->
          <div v-if="activeService.steps && activeService.steps.length > 0" class="space-y-4">
            <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400">
              {{ portalStore.t.interactiveProcedure }}
            </h3>

            <!-- Steps Tabs -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
              <button
                v-for="step in activeService.steps"
                :key="step.id"
                @click="currentStep = step.step_number"
                class="p-3 rounded-xl border text-left transition"
                :class="currentStep === step.step_number
                  ? 'border-emerald-500 bg-emerald-50/50 dark:bg-emerald-950/30'
                  : 'border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/40'"
              >
                <div class="text-[10px] font-bold uppercase tracking-wider text-emerald-600 mb-0.5">
                  {{ portalStore.t.stage }} {{ step.step_number }}
                </div>
                <div class="font-bold text-xs text-slate-800 dark:text-white line-clamp-1">
                  {{ step.title }}
                </div>
              </button>
            </div>

            <!-- Active Step Showcase Card -->
            <div
              v-for="step in activeService.steps"
              :key="step.id"
            >
              <div
                v-if="currentStep === step.step_number"
                class="civic-card p-6 bg-gradient-to-r from-emerald-50/50 to-transparent dark:from-emerald-950/20 border-emerald-300/40 space-y-3"
              >
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-bold">
                    {{ step.step_number }}
                  </div>
                  <div>
                    <h4 class="font-bold text-base text-slate-900 dark:text-white">{{ step.title }}</h4>
                    <p class="text-xs text-slate-500">{{ portalStore.t.officialStandard }}</p>
                  </div>
                </div>
                <p class="text-xs md:text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                  {{ step.description }}
                </p>
              </div>
            </div>
          </div>

          <!-- Application Box & CTA -->
          <div class="pt-4 border-t border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="text-xs text-slate-500 space-y-0.5 text-center sm:text-left">
              <div><strong>{{ portalStore.t.processingDuration }}:</strong> {{ portalStore.t.businessDays }}</div>
              <div><strong>{{ portalStore.t.paymentGateways }}:</strong> Telebirr, Commercial Bank of Ethiopia</div>
            </div>

            <button
              v-if="!applicationSubmitted"
              @click="applicationSubmitted = true"
              class="btn btn-primary text-xs px-6 py-3 rounded-xl shadow-lg w-full sm:w-auto"
            >
              <UiIcon name="check-circle" :size="16" />
              <span>{{ portalStore.t.startApplication }}</span>
            </button>
            <div v-else class="text-xs font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/40 px-4 py-2 rounded-xl border border-emerald-300">
              {{ portalStore.t.appTrackingNotice }}
            </div>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>
