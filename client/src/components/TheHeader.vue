<script setup lang="ts">
import { ref, onMounted, onUnmounted, watch } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { usePortalStore } from '../stores/portal';
import UiIcon from './UiIcon.vue';
import type { SupportedLocale } from '../types';

const router = useRouter();
const route = useRoute();
const portalStore = usePortalStore();

const activeMegaDropdown = ref<number | null>(null);
const isMobileMenuOpen = ref<boolean>(false);
const isNoticeBoardModalOpen = ref<boolean>(false);
const isLangDropdownOpen = ref<boolean>(false);
const expandedMobileMenus = ref<Record<number, boolean>>({});

const languages: { code: SupportedLocale; label: string; native: string }[] = [
  { code: 'am', label: 'Amharic', native: 'አማርኛ' },
  { code: 'en', label: 'English', native: 'English' },
  { code: 'om', label: 'Oromoo', native: 'Afaan Oromoo' },
];

function selectLanguage(code: SupportedLocale) {
  portalStore.setLocale(code);
  isLangDropdownOpen.value = false;
}

function toggleLangDropdown() {
  isLangDropdownOpen.value = !isLangDropdownOpen.value;
}

function isActive(url: string) {
  if (url === '/' && route.path === '/') return true;
  if (url !== '/' && route.path.startsWith(url)) return true;
  return false;
}

function handleNavClick(url: string) {
  activeMegaDropdown.value = null;
  isMobileMenuOpen.value = false;
  if (url.startsWith('http')) {
    window.open(url, '_blank');
  } else {
    router.push(url);
  }
}

function toggleDropdown(id: number, url: string, hasChildren: boolean) {
  if (hasChildren) {
    activeMegaDropdown.value = activeMegaDropdown.value === id ? null : id;
  } else {
    handleNavClick(url);
  }
}

function toggleMobileAccordion(id: number) {
  expandedMobileMenus.value[id] = !expandedMobileMenus.value[id];
}

// Global keyboard shortcut and accessibility
function handleKeydown(e: KeyboardEvent) {
  if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
    e.preventDefault();
    portalStore.openSearch();
  }
  if (e.key === 'Escape') {
    activeMegaDropdown.value = null;
    isLangDropdownOpen.value = false;
    if (isMobileMenuOpen.value) {
      isMobileMenuOpen.value = false;
    }
    if (isNoticeBoardModalOpen.value) {
      isNoticeBoardModalOpen.value = false;
    }
  }
}

function handleClickOutside(e: MouseEvent) {
  const target = e.target as HTMLElement;
  if (!target.closest('.mega-nav-item')) {
    activeMegaDropdown.value = null;
  }
  if (!target.closest('.lang-dropdown')) {
    isLangDropdownOpen.value = false;
  }
}

watch(isMobileMenuOpen, (isOpen) => {
  if (isOpen) {
    document.body.style.overflow = 'hidden';
  } else {
    document.body.style.overflow = '';
  }
});

onMounted(() => {
  window.addEventListener('keydown', handleKeydown);
  document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
  window.removeEventListener('keydown', handleKeydown);
  document.removeEventListener('click', handleClickOutside);
  document.body.style.overflow = '';
});
</script>

<template>
  <header class="portal-header sticky top-0 z-50 transition-all" role="banner">
    <!-- 1. Top Utility Bar: Verified Contact, Operating Hours, Language, Theme -->
    <div class="utility-bar bg-[#F1F5F9] dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 text-xs py-1.5 px-3 sm:px-4 transition-colors">
      <div class="container flex items-center justify-between gap-3">
        <!-- Hotlines & Operating Hours -->
        <div class="flex items-center gap-3 overflow-x-auto py-0.5 no-scrollbar text-xs min-w-0">
          <div class="flex items-center gap-1.5 font-bold text-[#004370] dark:text-sky-400 shrink-0">
            <UiIcon name="phone" :size="13" />
            <span class="hidden sm:inline">{{ portalStore.t.emergencyHotlines }}:</span>
            <span class="sm:hidden font-bold">24/7:</span>
          </div>
          <div class="flex items-center gap-2.5 whitespace-nowrap text-xs font-semibold">
            <a
              v-for="em in portalStore.emergencyContacts.slice(0, 3)"
              :key="em.id"
              :href="`tel:${em.phone}`"
              class="inline-flex items-center gap-1 hover:text-[#004370] dark:hover:text-sky-300 transition"
            >
              <span class="text-slate-900 dark:text-slate-100 font-bold">{{ em.phone.split('/')[0].trim() }}</span>
              <span class="text-slate-500 text-[11px] font-normal hidden md:inline">({{ em.title.split(' ')[0] }})</span>
            </a>
          </div>
          <div class="hidden lg:flex items-center gap-1 text-slate-500 text-[11px] border-l border-slate-300 dark:border-slate-700 pl-3">
            <UiIcon name="clock" :size="12" />
            <span>Mon – Fri: 8:30 AM – 5:30 PM</span>
          </div>
        </div>

        <!-- Social Icons, Internal Board & Language Selector -->
        <div class="flex items-center gap-2 sm:gap-3 shrink-0">
          <!-- Circular Social Media Links -->
          <div class="hidden md:flex items-center gap-1.5">
            <a
              href="https://facebook.com/kombolchacity"
              target="_blank"
              rel="noopener noreferrer"
              class="w-6 h-6 rounded-full bg-[#004370] text-white flex items-center justify-center hover:opacity-85 transition"
              title="Facebook"
              aria-label="Kombolcha City on Facebook"
            >
              <UiIcon name="facebook" :size="12" />
            </a>
            <a
              href="https://x.com/kombolchacity"
              target="_blank"
              rel="noopener noreferrer"
              class="w-6 h-6 rounded-full bg-[#004370] text-white flex items-center justify-center hover:opacity-85 transition"
              title="X (Twitter)"
              aria-label="Kombolcha City on X"
            >
              <UiIcon name="twitter" :size="12" />
            </a>
            <a
              href="https://youtube.com/@kombolchacity"
              target="_blank"
              rel="noopener noreferrer"
              class="w-6 h-6 rounded-full bg-[#004370] text-white flex items-center justify-center hover:opacity-85 transition"
              title="YouTube"
              aria-label="Kombolcha City on YouTube"
            >
              <UiIcon name="youtube" :size="12" />
            </a>
            <a
              href="https://t.me/kombolchacity"
              target="_blank"
              rel="noopener noreferrer"
              class="w-6 h-6 rounded-full bg-[#004370] text-white flex items-center justify-center hover:opacity-85 transition"
              title="Telegram"
              aria-label="Kombolcha City on Telegram"
            >
              <UiIcon name="send" :size="11" />
            </a>
          </div>

          <!-- Internal Notice Board Trigger -->
          <button
            @click="isNoticeBoardModalOpen = true"
            class="px-2 sm:px-2.5 py-1 rounded-md bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-700 transition font-semibold text-xs text-[#004370] dark:text-sky-300 flex items-center gap-1 cursor-pointer shadow-xs"
            title="Public notices and bulletins"
          >
            <UiIcon name="file-text" :size="12" />
            <span class="hidden sm:inline">{{ portalStore.t.internalBoard }}</span>
            <span class="sm:hidden">Notices</span>
          </button>

          <!-- Language Selector Dropdown (click-based for accessibility) -->
          <div class="relative lang-dropdown">
            <button
              @click.stop="toggleLangDropdown"
              class="flex items-center gap-1 px-2.5 py-1 rounded-md bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 font-semibold text-xs hover:bg-slate-100 dark:hover:bg-slate-700 transition cursor-pointer shadow-xs"
              aria-label="Change Portal Language"
              :aria-expanded="isLangDropdownOpen"
              aria-haspopup="listbox"
            >
              <UiIcon name="globe" :size="13" />
              <span class="uppercase font-bold text-[#004370] dark:text-sky-300">{{ portalStore.locale }}</span>
              <UiIcon name="chevron-down" :size="11" class="transition-transform duration-200" :class="{ 'rotate-180': isLangDropdownOpen }" />
            </button>
            <transition name="dropdown-fade">
              <div v-if="isLangDropdownOpen" class="absolute right-0 top-full mt-1 z-50 w-40">
                <div class="p-1 rounded-lg shadow-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900" role="listbox" :aria-label="portalStore.t.language">
                  <button
                    v-for="l in languages"
                    :key="l.code"
                    @click="selectLanguage(l.code)"
                    role="option"
                    :aria-selected="portalStore.locale === l.code"
                    class="w-full text-left px-3 py-1.5 rounded-md text-xs font-medium flex items-center justify-between hover:bg-slate-100 dark:hover:bg-slate-800 transition cursor-pointer"
                    :class="{ 'font-bold text-[#004370] dark:text-sky-400 bg-blue-50/70 dark:bg-slate-800/80': portalStore.locale === l.code }"
                  >
                    <span>{{ l.native }}</span>
                    <UiIcon v-if="portalStore.locale === l.code" name="check" :size="12" />
                  </button>
                </div>
              </div>
            </transition>
          </div>

          <!-- Theme Toggle -->
          <button
            @click="portalStore.toggleDarkMode()"
            class="p-1.5 rounded-md hover:bg-slate-200 dark:hover:bg-slate-800 transition text-slate-600 dark:text-slate-300 cursor-pointer"
            :title="portalStore.isDark ? portalStore.t.lightTheme : portalStore.t.darkTheme"
            :aria-label="portalStore.isDark ? 'Switch to Light Mode' : 'Switch to Dark Mode'"
          >
            <UiIcon :name="portalStore.isDark ? 'sun' : 'moon'" :size="14" />
          </button>
        </div>
      </div>
    </div>

    <!-- 2. Main Navigation Bar -->
    <div class="bg-[#004370] text-white shadow-md">
      <div class="container flex items-center justify-between h-14 md:h-16 gap-3">
        <!-- Municipal Identity -->
        <router-link to="/" class="flex items-center gap-2.5 sm:gap-3 py-1 group shrink-0 min-w-0" aria-label="Kombolcha City Administration Home">
          <div class="w-9 h-9 md:w-10 md:h-10 rounded-xl bg-white/10 border border-white/20 p-1 flex items-center justify-center transform group-hover:scale-105 transition-transform duration-200 shadow-sm shrink-0">
            <UiIcon name="landmark" :size="20" class="text-amber-300" />
          </div>
          <div class="min-w-0">
            <h2 class="font-extrabold text-sm sm:text-base leading-tight tracking-tight text-white group-hover:text-amber-300 transition-colors truncate">
              {{ portalStore.siteInfo.site_name || portalStore.t.portalName }}
            </h2>
            <p class="hidden sm:block text-[11px] text-white/70 font-normal leading-tight mt-0.5 truncate max-w-xs md:max-w-sm">
              {{ portalStore.siteInfo.site_tagline || portalStore.t.portalTagline }}
            </p>
          </div>
        </router-link>

        <!-- Desktop Navigation Items -->
        <nav class="hidden xl:flex items-center gap-0.5" aria-label="Main Navigation">
          <div
            v-for="item in portalStore.megaMenu"
            :key="item.id"
            class="relative mega-nav-item"
            @mouseenter="activeMegaDropdown = item.id"
            @mouseleave="activeMegaDropdown = null"
          >
            <button
              @click="toggleDropdown(item.id, item.url, Boolean(item.children && item.children.length > 0))"
              class="flex items-center gap-1 px-2 xl:px-2.5 py-1.5 rounded-lg text-xs xl:text-[13px] font-semibold text-white/90 hover:text-white hover:bg-white/15 transition whitespace-nowrap cursor-pointer"
              :class="{
                'bg-white/20 text-white font-bold': isActive(item.url) || activeMegaDropdown === item.id
              }"
              :aria-expanded="activeMegaDropdown === item.id"
              :aria-haspopup="item.children && item.children.length > 0 ? 'true' : undefined"
            >
              <UiIcon v-if="item.icon" :name="item.icon" :size="14" />
              <span>{{ item.label }}</span>
              <UiIcon v-if="item.children && item.children.length > 0" name="chevron-down" :size="11" class="opacity-70" />
            </button>

            <!-- Dropdown Menu -->
            <transition name="dropdown-fade">
              <div
                v-if="activeMegaDropdown === item.id && item.children && item.children.length > 0"
                class="absolute left-0 top-full pt-1.5 w-72 z-50"
              >
                <div class="p-2 rounded-xl shadow-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                  <div class="text-[11px] font-bold uppercase tracking-wider text-[#004370] dark:text-sky-400 px-3 py-1.5 mb-1 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                    <span>{{ item.label }}</span>
                    <UiIcon name="arrow-right" :size="11" class="opacity-60" />
                  </div>
                  <ul class="space-y-0.5" role="menu">
                    <li v-for="child in item.children" :key="child.id" role="none">
                      <button
                        @click="handleNavClick(child.url)"
                        role="menuitem"
                        class="w-full text-left flex items-center justify-between px-3 py-2 rounded-lg text-xs font-medium text-slate-700 dark:text-slate-300 hover:bg-sky-50 dark:hover:bg-slate-800 hover:text-[#004370] dark:hover:text-sky-300 transition cursor-pointer"
                      >
                        <span>{{ child.label }}</span>
                        <UiIcon name="chevron-right" :size="12" class="opacity-40" />
                      </button>
                    </li>
                  </ul>
                </div>
              </div>
            </transition>
          </div>
        </nav>

        <!-- Right Controls: Search & Mobile Toggle -->
        <div class="flex items-center gap-2 shrink-0">
          <!-- Global Search Trigger -->
          <button
            @click="portalStore.openSearch()"
            class="flex items-center gap-2 px-2.5 sm:px-3 py-1.5 rounded-lg bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs transition cursor-pointer shadow-xs"
            title="Search Portal (Ctrl+K)"
            aria-label="Search City Portal"
          >
            <UiIcon name="search" :size="14" />
            <span class="hidden 2xl:inline">{{ portalStore.t.searchPlaceholder.slice(0, 16) }}…</span>
            <span class="hidden md:inline 2xl:hidden">Search</span>
            <kbd class="hidden xl:inline-block px-1.5 py-0.5 text-[10px] font-mono bg-white/20 rounded">⌘K</kbd>
          </button>

          <!-- Mobile Hamburger Toggle -->
          <button
            @click="isMobileMenuOpen = !isMobileMenuOpen"
            class="xl:hidden p-2 rounded-lg text-white hover:bg-white/20 transition cursor-pointer"
            aria-label="Toggle navigation menu"
            :aria-expanded="isMobileMenuOpen"
          >
            <UiIcon :name="isMobileMenuOpen ? 'x' : 'menu'" :size="22" />
          </button>
        </div>
      </div>
    </div>

    <!-- 3. Mobile Off-Canvas Navigation Drawer -->
    <transition name="drawer-slide">
      <div
        v-if="isMobileMenuOpen"
        class="xl:hidden fixed inset-0 z-50"
        role="dialog"
        aria-modal="true"
        aria-label="Navigation Menu"
      >
        <!-- Backdrop -->
        <div
          class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm"
          @click="isMobileMenuOpen = false"
        ></div>

        <!-- Drawer Panel -->
        <div class="drawer-panel absolute left-0 top-0 w-4/5 max-w-sm h-full bg-white dark:bg-slate-900 shadow-2xl flex flex-col text-slate-800 dark:text-slate-100">
          <!-- Mobile Drawer Header -->
          <div class="flex items-center justify-between p-4 border-b border-slate-200 dark:border-slate-800">
            <div class="flex items-center gap-2.5">
              <div class="w-8 h-8 rounded-lg bg-[#004370] text-amber-300 flex items-center justify-center font-bold">
                <UiIcon name="landmark" :size="18" />
              </div>
              <div>
                <h3 class="font-bold text-sm text-slate-900 dark:text-white leading-tight">
                  {{ portalStore.siteInfo.site_name || portalStore.t.portalName }}
                </h3>
                <p class="text-[10px] text-slate-500 dark:text-slate-400">Kombolcha, Ethiopia</p>
              </div>
            </div>
            <button
              @click="isMobileMenuOpen = false"
              class="p-1.5 rounded-lg text-slate-500 hover:text-slate-700 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition cursor-pointer"
              aria-label="Close navigation"
            >
              <UiIcon name="x" :size="20" />
            </button>
          </div>

          <!-- Mobile Links Accordion (scrollable) -->
          <div class="flex-1 overflow-y-auto p-4">
            <div class="space-y-0.5">
              <div
                v-for="item in portalStore.megaMenu"
                :key="item.id"
                class="border-b border-slate-100 dark:border-slate-800/80"
              >
                <div class="flex items-center justify-between">
                  <button
                    @click="handleNavClick(item.url)"
                    class="flex items-center gap-2.5 py-2.5 text-sm font-bold text-slate-800 dark:text-slate-100 text-left hover:text-[#004370] dark:hover:text-sky-400 transition cursor-pointer flex-grow"
                    :class="{ 'text-[#004370] dark:text-sky-400 font-extrabold': isActive(item.url) }"
                  >
                    <UiIcon v-if="item.icon" :name="item.icon" :size="15" class="text-[#004370] dark:text-sky-400 shrink-0" />
                    <span>{{ item.label }}</span>
                  </button>

                  <button
                    v-if="item.children && item.children.length > 0"
                    @click="toggleMobileAccordion(item.id)"
                    class="p-2 text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 cursor-pointer rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition"
                    :aria-label="`Toggle sub-items for ${item.label}`"
                    :aria-expanded="!!expandedMobileMenus[item.id]"
                  >
                    <UiIcon
                      name="chevron-down"
                      :size="14"
                      class="transition-transform duration-200"
                      :class="{ 'rotate-180': expandedMobileMenus[item.id] }"
                    />
                  </button>
                </div>

                <!-- Sub-items Accordion -->
                <div
                  v-if="item.children && expandedMobileMenus[item.id]"
                  class="pl-8 pr-2 py-1 space-y-0.5 bg-slate-50 dark:bg-slate-800/50 rounded-lg mb-2"
                >
                  <button
                    v-for="sub in item.children"
                    :key="sub.id"
                    @click="handleNavClick(sub.url)"
                    class="w-full text-left block py-2 text-xs text-slate-600 dark:text-slate-300 hover:text-[#004370] dark:hover:text-sky-400 transition cursor-pointer font-medium rounded-md px-2 hover:bg-white dark:hover:bg-slate-700"
                  >
                    {{ sub.label }}
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Mobile Drawer Footer Controls -->
          <div class="p-4 border-t border-slate-200 dark:border-slate-800 space-y-3">
            <button
              @click="isNoticeBoardModalOpen = true; isMobileMenuOpen = false"
              class="w-full py-2.5 rounded-xl bg-[#004370] text-white font-semibold text-xs flex items-center justify-center gap-2 shadow-sm cursor-pointer hover:bg-[#003554] transition"
            >
              <UiIcon name="file-text" :size="14" />
              <span>{{ portalStore.t.internalBoard }}</span>
            </button>

            <div class="flex items-center justify-between text-xs text-slate-500">
              <span>Hotline: 033 551 0122</span>
              <button
                @click="portalStore.toggleDarkMode()"
                class="flex items-center gap-1 font-semibold text-slate-700 dark:text-slate-300 cursor-pointer hover:text-[#004370] dark:hover:text-sky-400 transition"
              >
                <UiIcon :name="portalStore.isDark ? 'sun' : 'moon'" :size="14" />
                <span>{{ portalStore.isDark ? 'Light' : 'Dark' }}</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </transition>

    <!-- 4. Notice Board Modal -->
    <transition name="modal-fade">
      <div
        v-if="isNoticeBoardModalOpen"
        class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4"
        @click.self="isNoticeBoardModalOpen = false"
        role="dialog"
        aria-modal="true"
        aria-labelledby="notice-board-title"
      >
        <div class="bg-white dark:bg-slate-900 rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100">
          <div class="flex items-center justify-between pb-3 border-b border-slate-200 dark:border-slate-800">
            <div class="flex items-center gap-2">
              <span class="w-8 h-8 rounded-lg bg-[#004370] text-white flex items-center justify-center">
                <UiIcon name="file-text" :size="16" />
              </span>
              <h3 id="notice-board-title" class="font-bold text-base">{{ portalStore.t.internalBoard }}</h3>
            </div>
            <button
              @click="isNoticeBoardModalOpen = false"
              class="text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 p-1 cursor-pointer rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition"
              aria-label="Close dialog"
            >
              <UiIcon name="x" :size="18" />
            </button>
          </div>

          <div class="py-4 space-y-3 text-xs leading-relaxed">
            <div class="p-3 bg-blue-50 dark:bg-slate-800/60 rounded-xl border border-blue-200 dark:border-slate-700">
              <span class="font-bold text-[#004370] dark:text-sky-400 block mb-1">
                <UiIcon name="info" :size="13" class="inline mr-1" />
                {{ portalStore.t.internalBoardTitle }}
              </span>
              <p class="text-slate-600 dark:text-slate-300">
                {{ portalStore.t.internalBoardDesc }}
              </p>
            </div>
            <ul class="space-y-2">
              <li class="flex items-center justify-between p-2.5 rounded-lg bg-slate-50 dark:bg-slate-800">
                <span class="font-medium text-slate-800 dark:text-slate-200">{{ portalStore.t.notice1Title }}</span>
                <span class="text-[11px] text-slate-500 shrink-0 ml-3">{{ portalStore.t.notice1Date }}</span>
              </li>
              <li class="flex items-center justify-between p-2.5 rounded-lg bg-slate-50 dark:bg-slate-800">
                <span class="font-medium text-slate-800 dark:text-slate-200">{{ portalStore.t.notice2Title }}</span>
                <span class="text-[11px] text-slate-500 shrink-0 ml-3">{{ portalStore.t.notice2Date }}</span>
              </li>
            </ul>
          </div>

          <div class="pt-3 border-t border-slate-200 dark:border-slate-800 flex justify-end">
            <button
              @click="isNoticeBoardModalOpen = false"
              class="px-4 py-2 bg-[#004370] text-white font-semibold text-xs rounded-lg hover:bg-[#003554] transition cursor-pointer"
            >
              {{ portalStore.t.close }}
            </button>
          </div>
        </div>
      </div>
    </transition>
  </header>
</template>

<style scoped>
.dropdown-fade-enter-active,
.dropdown-fade-leave-active {
  transition: opacity 0.15s ease, transform 0.15s ease;
}
.dropdown-fade-enter-from,
.dropdown-fade-leave-to {
  opacity: 0;
  transform: translateY(-4px);
}

/* Mobile drawer slide from left */
.drawer-slide-enter-active {
  transition: opacity 0.25s ease;
}
.drawer-slide-enter-active .drawer-panel {
  transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}
.drawer-slide-leave-active {
  transition: opacity 0.2s ease;
}
.drawer-slide-leave-active .drawer-panel {
  transition: transform 0.2s ease;
}
.drawer-slide-enter-from {
  opacity: 0;
}
.drawer-slide-enter-from .drawer-panel {
  transform: translateX(-100%);
}
.drawer-slide-leave-to {
  opacity: 0;
}
.drawer-slide-leave-to .drawer-panel {
  transform: translateX(-100%);
}

/* Modal fade */
.modal-fade-enter-active,
.modal-fade-leave-active {
  transition: opacity 0.2s ease;
}
.modal-fade-enter-from,
.modal-fade-leave-to {
  opacity: 0;
}
</style>
