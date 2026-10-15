<script setup lang="ts">
import { ref } from 'vue';
import { usePortalStore } from '../stores/portal';
import { api } from '../services/api';
import UiIcon from './UiIcon.vue';

const portalStore = usePortalStore();
const newsletterEmail = ref('');
const isSubscribed = ref(false);
const subscriptionMsg = ref('');
const subscribeError = ref('');

async function handleSubscribe() {
  if (!newsletterEmail.value || !newsletterEmail.value.includes('@')) return;
  subscribeError.value = '';
  try {
    const res = await api.subscribeNewsletter(newsletterEmail.value);
    if (res?.success) {
      isSubscribed.value = true;
      subscriptionMsg.value = res?.message || (portalStore.locale === 'am' ? 'ስለተመዘገቡ እናመሰግናለን!' : 'Thank you for subscribing to Kombolcha city alerts!');
      newsletterEmail.value = '';
    } else {
      subscribeError.value = res?.message || 'Subscription failed. Please check your email address.';
    }
  } catch (err: any) {
    subscribeError.value = err?.response?.data?.message || 'Unable to process subscription right now. Please try again.';
  }
}
</script>

<template>
  <!-- Kombolcha Official Deep Blue Footer (#08486C) matching kombolchacity.gov.et -->
  <footer class="bg-[#08486C] text-gray-300 pt-14 pb-8 mt-auto border-t border-sky-950">
    <div class="container">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-7 gap-8 pb-10 border-b border-white/10">
        <!-- Col 1: Identity & Crest (Col span 2) -->
        <div class="lg:col-span-2 space-y-4">
          <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-xl bg-white/10 border border-white/20 p-1 flex items-center justify-center text-amber-300 shadow">
              <UiIcon name="landmark" :size="24" />
            </div>
            <div>
              <h2 class="font-extrabold text-lg text-white leading-tight">
                {{ portalStore.siteInfo.site_name || portalStore.t.portalName }}
              </h2>
              <p class="text-xs text-sky-200 font-medium">
                {{ portalStore.siteInfo.site_tagline || portalStore.t.portalTagline }}
              </p>
            </div>
          </div>
          <p class="text-xs text-gray-300/90 leading-relaxed max-w-sm">
            {{ portalStore.t.servicesSubtitle }}
          </p>
          <div class="flex items-center gap-3 pt-2">
            <a href="https://facebook.com/kombolchacity" target="_blank" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition" title="Facebook">
              <UiIcon name="facebook" :size="14" />
            </a>
            <a href="https://x.com/kombolchacity" target="_blank" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition" title="X (Twitter)">
              <UiIcon name="twitter" :size="14" />
            </a>
            <a href="https://t.me/kombolchacity" target="_blank" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition" title="Telegram">
              <UiIcon name="send" :size="13" />
            </a>
            <a href="https://youtube.com/@kombolchacity" target="_blank" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition" title="YouTube">
              <UiIcon name="youtube" :size="14" />
            </a>
          </div>
        </div>

        <!-- Col 2: Services Quick Links -->
        <div class="lg:col-span-1">
          <h3 class="text-xs font-bold uppercase tracking-wider text-white mb-3">
            {{ portalStore.t.quickServices }}
          </h3>
          <ul class="space-y-2 text-xs">
            <li><router-link to="/services?type=step" class="hover:text-white transition">{{ portalStore.t.stepGuidesTab }}</router-link></li>
            <li><router-link to="/services#building-construction-permit" class="hover:text-white transition">{{ portalStore.t.allServices }}</router-link></li>
            <li><router-link to="/media?tab=tenders" class="hover:text-white transition">{{ portalStore.t.tendersTitle }}</router-link></li>
            <li><router-link to="/media?tab=vacancies" class="hover:text-white transition">{{ portalStore.t.vacanciesTitle }}</router-link></li>
          </ul>
        </div>

        <!-- Col 3: Government -->
        <div class="lg:col-span-1">
          <h3 class="text-xs font-bold uppercase tracking-wider text-white mb-3">
            {{ portalStore.t.administration }}
          </h3>
          <ul class="space-y-2 text-xs">
            <li><router-link to="/government#mayor" class="hover:text-white transition">{{ portalStore.t.mayorsOffice }}</router-link></li>
            <li><router-link to="/government#cabinet" class="hover:text-white transition">{{ portalStore.t.executiveCabinet }}</router-link></li>
            <li><router-link to="/government#council" class="hover:text-white transition">{{ portalStore.t.cityCouncil }}</router-link></li>
            <li><router-link to="/government#sub-cities" class="hover:text-white transition">{{ portalStore.t.kebelesBranches }}</router-link></li>
            <li><router-link to="/programs" class="hover:text-white transition">{{ portalStore.t.megaProjects }}</router-link></li>
          </ul>
        </div>

        <!-- Col 4: Community & Transparency -->
        <div class="lg:col-span-1">
          <h3 class="text-xs font-bold uppercase tracking-wider text-white mb-3">
            {{ portalStore.t.civicCommunity }}
          </h3>
          <ul class="space-y-2 text-xs">
            <li><router-link to="/forum" class="hover:text-white transition">{{ portalStore.t.forumTitle }}</router-link></li>
            <li><router-link to="/resources" class="hover:text-white transition">{{ portalStore.t.resourcesTitle }}</router-link></li>
            <li><router-link to="/explore" class="hover:text-white transition">{{ portalStore.t.exploreCity }}</router-link></li>
            <li><router-link to="/contact" class="hover:text-white transition">{{ portalStore.t.contactTitle }}</router-link></li>
            <li><router-link to="/admin" class="hover:text-amber-300 font-semibold text-amber-300 transition">{{ portalStore.t.adminPortal }}</router-link></li>
          </ul>
        </div>

        <!-- Col 5: Subscription Form (Col span 2 - exact match to kombolchacity.gov.et) -->
        <div class="lg:col-span-2">
          <h3 class="text-white font-semibold text-sm mb-1">{{ portalStore.t.subscribeTitle }}</h3>
          <p class="text-white/80 text-xs mb-3">
            {{ portalStore.t.subscribeSubtitle }}
          </p>
          <form @submit.prevent="handleSubscribe" class="flex gap-2 flex-col sm:flex-row w-full">
            <input
              v-model="newsletterEmail"
              type="email"
              :placeholder="portalStore.t.enterEmail"
              required
              class="text-white text-xs border border-white/60 placeholder-white/70 bg-white/10 rounded-md py-2 px-3 w-full focus:outline-none focus:ring-2 focus:ring-sky-400"
            />
            <button
              type="submit"
              class="text-white font-semibold border border-white rounded-md py-2 px-4 hover:bg-white/20 transition whitespace-nowrap text-xs"
            >
              {{ portalStore.t.subscribeBtn }}
            </button>
          </form>
          <p v-if="isSubscribed" class="text-xs text-amber-300 mt-2 flex items-center gap-1 font-medium">
            <UiIcon name="check" :size="12" />
            {{ subscriptionMsg }}
          </p>
          <p v-if="subscribeError" class="text-xs text-red-300 mt-2 flex items-center gap-1 font-medium">
            <UiIcon name="alert-circle" :size="12" />
            {{ subscribeError }}
          </p>
          <p class="text-[11px] text-white/60 mt-2 leading-tight">
            {{ portalStore.t.subscribingConsent }}
          </p>
        </div>
      </div>

      <!-- Bottom Bar: Powered by MInT. Developed by 360Ground (matching kombolchacity.gov.et) -->
      <div class="pt-6 flex flex-col lg:flex-row items-center justify-between gap-4 text-xs text-white/70">
        <p class="text-white text-xs">
          © 2026 Powered by <a href="http://www.mint.gov.et/" target="_blank" rel="noopener noreferrer" class="underline hover:text-white font-semibold">MInT</a>. Developed by <a href="https://360ground.com" target="_blank" rel="noopener noreferrer" class="underline hover:text-white font-semibold">360Ground</a>. All rights reserved.
        </p>
        <ul class="flex items-center gap-4 text-xs text-white/80">
          <li><router-link to="/resources#privacy" class="hover:text-white">{{ portalStore.t.privacyPolicy }}</router-link></li>
          <li><span class="text-white/40">•</span></li>
          <li><router-link to="/resources#terms" class="hover:text-white">{{ portalStore.t.termsOfService }}</router-link></li>
          <li><span class="text-white/40">•</span></li>
          <li><router-link to="/resources#accessibility" class="hover:text-white">{{ portalStore.t.cookieSettings }}</router-link></li>
        </ul>
      </div>
    </div>
  </footer>
</template>
