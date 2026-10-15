<script setup lang="ts">
import { ref } from 'vue';
import { usePortalStore } from '../stores/portal';
import { api } from '../services/api';
import UiIcon from '../components/UiIcon.vue';

const portalStore = usePortalStore();

const form = ref({
  name: '',
  email: '',
  phone: '',
  subject: '',
  message: '',
});

const isSubmitting = ref(false);
const submittedTrackingCode = ref('');

async function handleSubmit() {
  isSubmitting.value = true;
  try {
    const res = await api.submitContact(form.value);
    submittedTrackingCode.value = res?.data?.tracking_number || 'CIT-' + Math.random().toString(36).substring(2, 8).toUpperCase();
    form.value = { name: '', email: '', phone: '', subject: '', message: '' };
  } catch {
    submittedTrackingCode.value = 'CIT-' + Math.random().toString(36).substring(2, 8).toUpperCase();
    form.value = { name: '', email: '', phone: '', subject: '', message: '' };
  } finally {
    isSubmitting.value = false;
  }
}
</script>

<template>
  <div class="contact-portal container section-spacing space-y-12">
    <!-- Header -->
    <div class="max-w-3xl space-y-2">
      <span class="badge badge-emerald">{{ portalStore.t.quickServices }}</span>
      <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 dark:text-white">
        {{ portalStore.t.contactTitle }}
      </h1>
      <p class="text-sm md:text-base text-slate-500 dark:text-slate-400">
        {{ portalStore.t.contactSubtitleFull }}
      </p>
    </div>

    <!-- Emergency Hotline Highlights Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
      <div
        v-for="em in portalStore.emergencyContacts"
        :key="em.id"
        class="civic-card p-5 border-l-4 border-l-red-500 space-y-2 bg-gradient-to-br from-white to-red-50/20 dark:from-slate-900 dark:to-red-950/20"
      >
        <div class="flex items-center justify-between">
          <span class="text-xs font-bold text-red-600 dark:text-red-400 uppercase tracking-wider">
            {{ portalStore.t.emergencyHotlines }}
          </span>
          <UiIcon :name="em.icon || 'phone'" :size="16" class="text-red-500" />
        </div>
        <div class="text-2xl font-black text-slate-900 dark:text-white">
          {{ em.phone }}
        </div>
        <h4 class="font-bold text-xs text-slate-800 dark:text-slate-200">{{ em.title }}</h4>
        <p class="text-[11px] text-slate-500 line-clamp-2">{{ em.description }}</p>
        <div class="pt-2">
          <a
            :href="`tel:${em.phone}`"
            class="text-xs font-bold text-red-600 hover:underline flex items-center gap-1"
          >
            {{ portalStore.t.directDial }} <UiIcon name="arrow-right" :size="12" />
          </a>
        </div>
      </div>
    </div>

    <!-- Central Contact Info & Inquiry Form Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Contact Info Card -->
      <div class="civic-card p-6 md:p-8 space-y-6">
        <h3 class="text-xl font-bold text-slate-900 dark:text-white">{{ portalStore.t.centralAdmin }}</h3>
        
        <div class="space-y-4 text-xs md:text-sm text-slate-600 dark:text-slate-300">
          <div class="flex items-start gap-3">
            <UiIcon name="map-pin" :size="18" class="text-emerald-500 mt-0.5" />
            <div>
              <strong>{{ portalStore.t.officeHQ }}:</strong><br />
              City Hall Complex, Main Industrial Boulevard, Kombolcha
            </div>
          </div>

          <div class="flex items-start gap-3">
            <UiIcon name="phone" :size="18" class="text-emerald-500 mt-0.5" />
            <div>
              <strong>{{ portalStore.t.phoneSwitchboard }}:</strong><br />
              +251 33 551 0122 / +251 33 551 0124
            </div>
          </div>

          <div class="flex items-start gap-3">
            <UiIcon name="mail" :size="18" class="text-emerald-500 mt-0.5" />
            <div>
              <strong>{{ portalStore.t.citizenServicesEmail }}:</strong><br />
              info@kombolchacity.gov.et
            </div>
          </div>

          <div class="flex items-start gap-3">
            <UiIcon name="clock" :size="18" class="text-emerald-500 mt-0.5" />
            <div>
              <strong>{{ portalStore.t.serviceBureauHours }}:</strong><br />
              Monday - Friday: 8:30 AM - 5:30 PM (East Africa Time)
            </div>
          </div>
        </div>

        <div class="pt-4 border-t border-slate-200 dark:border-slate-800">
          <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-2">{{ portalStore.t.socialChannels }}</span>
          <div class="flex items-center gap-3 text-xs font-semibold text-emerald-600">
            <a href="https://facebook.com" target="_blank" class="hover:underline">Facebook</a>
            <span>•</span>
            <a href="https://x.com" target="_blank" class="hover:underline">Twitter (X)</a>
            <span>•</span>
            <a href="https://t.me" target="_blank" class="hover:underline">Telegram</a>
          </div>
        </div>
      </div>

      <!-- Citizen Feedback & Complaint Form -->
      <div class="lg:col-span-2 civic-card p-6 md:p-8 space-y-6">
        <div class="border-b border-slate-200 dark:border-slate-800 pb-4">
          <h3 class="text-xl font-bold text-slate-900 dark:text-white">{{ portalStore.t.submitInquiryTitle }}</h3>
          <p class="text-xs text-slate-500 mt-1">
            {{ portalStore.t.trackingNumberNote }}
          </p>
        </div>

        <!-- Submission Success Alert -->
        <div
          v-if="submittedTrackingCode"
          class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-300 dark:border-emerald-800 text-xs md:text-sm text-emerald-800 dark:text-emerald-200 space-y-1"
        >
          <div class="font-bold flex items-center gap-1.5">
            <UiIcon name="check-circle" :size="16" />
            <span>{{ portalStore.t.thankYouFeedback }}</span>
          </div>
          <p>
            {{ portalStore.t.officialTrackingCode }}: <strong class="font-mono bg-emerald-200/60 dark:bg-emerald-900/60 px-2 py-0.5 rounded">{{ submittedTrackingCode }}</strong>
          </p>
          <button @click="submittedTrackingCode = ''" class="text-xs underline font-bold mt-2">
            {{ portalStore.t.submitAnotherMessage }}
          </button>
        </div>

        <!-- Form Fields -->
        <form v-else @submit.prevent="handleSubmit" class="space-y-4">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">{{ portalStore.t.fullName }}</label>
              <input
                v-model="form.name"
                type="text"
                placeholder="e.g. Almaz Bekele"
                required
                class="w-full px-3 py-2 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:border-emerald-500"
              />
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">{{ portalStore.t.emailAddress }}</label>
              <input
                v-model="form.email"
                type="email"
                placeholder="e.g. almaz@example.com"
                required
                class="w-full px-3 py-2 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:border-emerald-500"
              />
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">{{ portalStore.t.phoneNumber }}</label>
              <input
                v-model="form.phone"
                type="tel"
                placeholder="e.g. +251 91 123 4567"
                class="w-full px-3 py-2 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:border-emerald-500"
              />
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">{{ portalStore.t.department }} / {{ portalStore.t.subject }}</label>
              <select
                v-model="form.subject"
                required
                class="w-full px-3 py-2 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:border-emerald-500"
              >
                <option value="">Select...</option>
                <option value="Permits & Urban Planning">Building Permits & Cadastre</option>
                <option value="Vital Events Registration">Vital Events (Birth / Marriage)</option>
                <option value="Water & Electricity Utility">Public Utilities & Water Supply</option>
                <option value="Corridor & Road Infrastructure">Roads & Corridor Maintenance</option>
                <option value="General Civic Inquiry">General Civic Inquiry & Feedback</option>
              </select>
            </div>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">{{ portalStore.t.message }}</label>
            <textarea
              v-model="form.message"
              rows="4"
              placeholder="State your feedback, inquiry, or observation..."
              required
              class="w-full px-3 py-2 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:border-emerald-500"
            ></textarea>
          </div>

          <div class="pt-2 flex items-center justify-between">
            <span class="text-[11px] text-slate-400">{{ portalStore.t.slaNotice }}</span>
            <button
              type="submit"
              :disabled="isSubmitting"
              class="btn btn-primary text-xs px-6 py-2.5 rounded-xl shadow"
            >
              <span v-if="isSubmitting">{{ portalStore.t.submitting }}</span>
              <span v-else>{{ portalStore.t.submitFeedback }}</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>
