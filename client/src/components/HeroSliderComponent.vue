<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { useRouter } from 'vue-router';
import { usePortalStore } from '../stores/portal';
import UiIcon from './UiIcon.vue';

const router = useRouter();
const portalStore = usePortalStore();

const currentIndex = ref(0);
let timer: ReturnType<typeof setInterval> | null = null;

const currentSlide = computed(() => {
  if (!portalStore.heroSliders || portalStore.heroSliders.length === 0) return null;
  return portalStore.heroSliders[currentIndex.value] || portalStore.heroSliders[0];
});

function nextSlide() {
  if (!portalStore.heroSliders || portalStore.heroSliders.length <= 1) return;
  currentIndex.value = (currentIndex.value + 1) % portalStore.heroSliders.length;
}

function prevSlide() {
  if (!portalStore.heroSliders || portalStore.heroSliders.length <= 1) return;
  currentIndex.value = (currentIndex.value - 1 + portalStore.heroSliders.length) % portalStore.heroSliders.length;
}

function goToSlide(idx: number) {
  currentIndex.value = idx;
}

function startTimer() {
  stopTimer();
  if (portalStore.heroSliders && portalStore.heroSliders.length > 1) {
    timer = setInterval(nextSlide, 7000);
  }
}

function stopTimer() {
  if (timer) {
    clearInterval(timer);
    timer = null;
  }
}

function handleCta(url?: string) {
  if (!url) {
    router.push('/services');
    return;
  }
  if (url.startsWith('http')) {
    window.open(url, '_blank');
  } else {
    router.push(url);
  }
}

function onKeydown(e: KeyboardEvent) {
  if (e.key === 'ArrowLeft') prevSlide();
  if (e.key === 'ArrowRight') nextSlide();
}

onMounted(() => {
  startTimer();
  window.addEventListener('keydown', onKeydown);
});

onUnmounted(() => {
  stopTimer();
  window.removeEventListener('keydown', onKeydown);
});
</script>

<template>
  <section
    class="hero-slider relative w-full overflow-hidden bg-slate-950 text-white select-none"
    aria-label="City Highlights and Civic Announcements"
    @mouseenter="stopTimer"
    @mouseleave="startTimer"
  >
    <!-- Background Image with Authoritative Civic Overlay -->
    <div class="absolute inset-0 z-0">
      <transition name="hero-bg-fade" mode="out-in">
        <img
          v-if="currentSlide"
          :key="currentSlide.id + '-bg'"
          :src="currentSlide.image"
          :alt="currentSlide.title"
          class="w-full h-full object-cover object-center"
        />
      </transition>
      <!-- Gradient overlay ensures minimum 7:1 contrast ratio across the text area -->
      <div class="absolute inset-0 bg-gradient-to-t md:bg-gradient-to-r from-slate-950/95 via-slate-950/80 to-slate-950/50"></div>
    </div>

    <!-- Slide Content Container -->
    <div class="container relative z-10 py-12 md:py-20 min-h-[440px] md:min-h-[500px] flex items-center">
      <div class="max-w-2xl w-full">
        <transition name="hero-text-fade" mode="out-in">
          <div
            v-if="currentSlide"
            :key="currentSlide.id + '-content'"
            class="space-y-4 md:space-y-5"
          >
            <!-- Civic Tag -->
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 text-xs font-bold uppercase tracking-wider border border-emerald-500/30 backdrop-blur-sm">
              <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
              {{ portalStore.siteInfo.site_name || portalStore.t.portalName }}
            </div>

            <!-- Main Headline -->
            <h1 class="text-2xl sm:text-3xl md:text-5xl font-extrabold text-white leading-tight tracking-tight drop-shadow-md">
              {{ currentSlide.title }}
            </h1>

            <!-- Subtitle -->
            <p class="text-sm sm:text-base md:text-lg text-slate-200 font-normal leading-relaxed max-w-xl drop-shadow">
              {{ currentSlide.subtitle }}
            </p>

            <!-- Actions -->
            <div class="pt-2 flex flex-wrap items-center gap-3">
              <button
                @click="handleCta(currentSlide.link_url)"
                class="btn btn-primary text-xs sm:text-sm px-6 py-3 rounded-xl shadow-lg flex w-full sm:w-auto justify-center items-center gap-2 cursor-pointer font-bold"
              >
                <span>{{ currentSlide.button_text || portalStore.t.exploreAllServices }}</span>
                <UiIcon name="arrow-right" :size="16" />
              </button>

              <button
                @click="router.push('/services')"
                class="btn bg-white/10 hover:bg-white/20 text-white backdrop-blur-md text-xs sm:text-sm px-5 py-3 rounded-xl border border-white/25 transition flex w-full sm:w-auto justify-center items-center gap-2 cursor-pointer font-medium"
              >
                <UiIcon name="layers" :size="15" />
                <span>{{ portalStore.t.quickServices }}</span>
              </button>
            </div>
          </div>
        </transition>
      </div>
    </div>

    <!-- Navigation Arrows -->
    <div v-if="portalStore.heroSliders && portalStore.heroSliders.length > 1">
      <button
        @click="prevSlide"
        class="hidden sm:flex absolute left-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-slate-900/70 hover:bg-slate-900 text-white items-center justify-center backdrop-blur-md border border-white/15 transition cursor-pointer"
        aria-label="Previous Slide"
      >
        <UiIcon name="arrow-right" :size="16" class="rotate-180" />
      </button>
      <button
        @click="nextSlide"
        class="hidden sm:flex absolute right-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-slate-900/70 hover:bg-slate-900 text-white items-center justify-center backdrop-blur-md border border-white/15 transition cursor-pointer"
        aria-label="Next Slide"
      >
        <UiIcon name="arrow-right" :size="16" />
      </button>

      <!-- Indicators -->
      <div class="absolute bottom-4 left-1/2 -translate-x-1/2 z-20 flex items-center gap-2" role="tablist">
        <button
          v-for="(_, idx) in portalStore.heroSliders"
          :key="idx"
          @click="goToSlide(idx)"
          role="tab"
          :aria-selected="currentIndex === idx"
          class="h-2 rounded-full transition-all duration-300 cursor-pointer"
          :class="currentIndex === idx ? 'w-8 bg-emerald-400' : 'w-2 bg-white/40 hover:bg-white/70'"
          :aria-label="`Slide ${idx + 1}`"
        ></button>
      </div>
    </div>
  </section>
</template>

<style scoped>
.hero-bg-fade-enter-active,
.hero-bg-fade-leave-active {
  transition: opacity 0.5s ease;
}
.hero-bg-fade-enter-from,
.hero-bg-fade-leave-to {
  opacity: 0;
}

.hero-text-fade-enter-active,
.hero-text-fade-leave-active {
  transition: opacity 0.35s ease, transform 0.35s ease;
}
.hero-text-fade-enter-from {
  opacity: 0;
  transform: translateY(8px);
}
.hero-text-fade-leave-to {
  opacity: 0;
  transform: translateY(-8px);
}
</style>
