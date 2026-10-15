<script setup lang="ts">
import { onMounted } from 'vue';
import { usePortalStore } from './stores/portal';
import TheHeader from './components/TheHeader.vue';
import TheFooter from './components/TheFooter.vue';
import GlobalSearchModal from './components/GlobalSearchModal.vue';

const portalStore = usePortalStore();

onMounted(() => {
  portalStore.fetchInitData();
});
</script>

<template>
  <div class="portal-root min-h-screen flex flex-col transition-colors duration-200">
    <!-- Header with Mega Menu & Emergency Ticker -->
    <TheHeader />

    <!-- Main View Outlet -->
    <main class="flex-grow">
      <router-view v-slot="{ Component }">
        <transition name="page-fade" mode="out-in">
          <component :is="Component" />
        </transition>
      </router-view>
    </main>

    <!-- Global Command Search Modal -->
    <GlobalSearchModal />

    <!-- Comprehensive Municipal Footer -->
    <TheFooter />
  </div>
</template>

<style scoped>
.page-fade-enter-active,
.page-fade-leave-active {
  transition: opacity 0.2s ease, transform 0.2s ease;
}

.page-fade-enter-from {
  opacity: 0;
  transform: translateY(4px);
}

.page-fade-leave-to {
  opacity: 0;
  transform: translateY(-4px);
}
</style>
