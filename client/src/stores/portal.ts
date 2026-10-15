import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import type {
  SupportedLocale,
  EmergencyContactItem,
  MenuItemNode,
  HeroSliderItem,
  PollItem,
} from '../types';
import { api } from '../services/api';
import { messages } from '../i18n/messages';

export const usePortalStore = defineStore('portal', () => {
  // 1. Language state
  const savedLocale = (localStorage.getItem('city_portal_locale') as SupportedLocale) || 'en';
  const locale = ref<SupportedLocale>(savedLocale);

  // 2. Dark mode state
  const isDarkSaved = localStorage.getItem('city_portal_theme') === 'dark';
  const isDark = ref<boolean>(isDarkSaved);
  if (isDarkSaved) {
    document.documentElement.classList.add('dark');
  }

  // 3. Search modal state
  const isSearchOpen = ref<boolean>(false);
  const isLoading = ref<boolean>(false);

  // 4. Data states
  const siteInfo = ref<Record<string, string>>({
    site_name: 'Kombolcha City Administration',
    site_tagline: 'Northern Ethiopia Premier Industrial, Logistics & Commercial Hub',
    city_population: '230,000+',
    city_elevation: '1,842 m (6,043 ft)',
    hotline_primary: '+251 33 551 0122 / 991',
  });

  const emergencyContacts = ref<EmergencyContactItem[]>([
    { id: 1, phone: '033 551 0122 / 991', icon: 'shield', title: 'Kombolcha City Police Department', description: '24/7 Rapid response crime reporting & urban security hotline' },
    { id: 2, phone: '033 551 0124 / 939', icon: 'flame', title: 'Kombolcha Fire & Disaster Risk Prevention', description: 'Emergency industrial fire extinguishing and disaster rescue' },
    { id: 3, phone: '033 551 0210 / 907', icon: 'ambulance', title: 'Kombolcha General Hospital & Ambulance', description: 'Immediate trauma triage, 24/7 emergency dispatch, and intensive care' },
    { id: 4, phone: '033 551 0450 / 988', icon: 'droplet', title: 'Kombolcha Water & Sewerage Enterprise', description: 'Main line burst repairs, industrial water supply, and drainage' },
  ]);

  const megaMenu = ref<MenuItemNode[]>([
    {
      id: 1, parent_id: null, label: 'About City', url: '/about', icon: 'info', sort_order: 1,
      children: [
        { id: 11, parent_id: 1, label: 'City Overview & Charter', url: '/about', sort_order: 1 },
        { id: 12, parent_id: 1, label: 'History & Heritage', url: '/about#history', sort_order: 2 },
        { id: 13, parent_id: 1, label: 'Sister Cities & Partnerships', url: '/about#sister-cities', sort_order: 3 },
      ]
    },
    {
      id: 2, parent_id: null, label: 'Government', url: '/government', icon: 'landmark', sort_order: 2,
      children: [
        { id: 21, parent_id: 2, label: 'Mayor & Executive Cabinet', url: '/government#mayor', sort_order: 1 },
        { id: 22, parent_id: 2, label: 'City Council', url: '/government#council', sort_order: 2 },
        { id: 23, parent_id: 2, label: 'Organizational Structure', url: '/government#structure', sort_order: 3 },
        { id: 24, parent_id: 2, label: 'Sub-Cities & Offices', url: '/government#sub-cities', sort_order: 4 },
      ]
    },
    {
      id: 3, parent_id: null, label: 'Citizen Services', url: '/services', icon: 'layers', sort_order: 3,
      children: [
        { id: 31, parent_id: 3, label: 'Step-by-Step Guides', url: '/services?type=step', sort_order: 1 },
        { id: 32, parent_id: 3, label: 'Building & Construction Permits', url: '/services#building-construction-permit', sort_order: 2 },
        { id: 33, parent_id: 3, label: 'Vital Events & Registration', url: '/services#vital-events-birth-registration', sort_order: 3 },
        { id: 34, parent_id: 3, label: 'Trade License Renewal', url: '/services#commercial-trade-license-renewal', sort_order: 4 },
      ]
    },
    {
      id: 4, parent_id: null, label: 'Media & News', url: '/media', icon: 'newspaper', sort_order: 4,
      children: [
        { id: 41, parent_id: 4, label: 'Official City News', url: '/media?tab=news', sort_order: 1 },
        { id: 42, parent_id: 4, label: 'Upcoming Civic Events', url: '/media?tab=events', sort_order: 2 },
        { id: 43, parent_id: 4, label: 'Civil Service Careers', url: '/media?tab=vacancies', sort_order: 3 },
        { id: 44, parent_id: 4, label: 'Public Procurement & Tenders', url: '/media?tab=tenders', sort_order: 4 },
      ]
    },
    {
      id: 5, parent_id: null, label: 'Explore City', url: '/explore', icon: 'compass', sort_order: 5,
      children: [
        { id: 51, parent_id: 5, label: 'Top Attractions & Parks', url: '/explore', sort_order: 1 },
        { id: 52, parent_id: 5, label: 'Key Municipal Programs', url: '/programs', sort_order: 2 },
      ]
    },
    {
      id: 6, parent_id: null, label: 'Transparency', url: '/resources', icon: 'file-text', sort_order: 6,
      children: [
        { id: 61, parent_id: 6, label: 'Directives & Regulations', url: '/resources', sort_order: 1 },
        { id: 62, parent_id: 6, label: 'Citizen Discussion Forum', url: '/forum', sort_order: 2 },
        { id: 63, parent_id: 6, label: 'Contact & Civic Inquiries', url: '/contact', sort_order: 3 },
      ]
    }
  ]);

  const heroSliders = ref<HeroSliderItem[]>([
    {
      id: 1,
      image: 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&w=1800&q=80',
      link_url: '/explore',
      title: 'Welcome to Kombolcha: Industrial & Logistics Gateway',
      subtitle: 'Home to premier industrial eco-parks, modern dry port logistics, and thriving international manufacturing.',
      button_text: 'Explore Kombolcha Hub',
    },
    {
      id: 2,
      image: 'https://images.unsplash.com/photo-1541888946425-d0fbb186156f?auto=format&fit=crop&w=1800&q=80',
      link_url: '/services',
      title: 'Digitized Municipal Services for Citizens & Investors',
      subtitle: 'Apply for trade licensing, land development permits, and track administrative requests online.',
      button_text: 'Access Citizen Services',
    },
    {
      id: 3,
      image: 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1800&q=80',
      link_url: '/programs',
      title: 'Urban Revitalization & Green Corridor Projects',
      subtitle: 'Modernizing city boulevards, solar streetlights, drainage canals, and public community parks.',
      button_text: 'View City Projects',
    }
  ]);

  const activePoll = ref<PollItem>({
    id: 1,
    slug: 'civic-priorities-neighborhood-improvement-2026',
    question: 'What civic improvement should be prioritized in your neighborhood this fiscal year?',
    description: 'Help the city cabinet allocate neighborhood participatory development funds directly where they matter most.',
    total_votes: 4190,
    options: [
      { id: 1, text: 'Pedestrian walkways & street solar lighting', votes: 1420 },
      { id: 2, text: 'Clean neighborhood parks & children playgrounds', votes: 980 },
      { id: 3, text: 'Piped clean water & drainage network overhaul', votes: 1150 },
      { id: 4, text: 'Digital youth innovation hubs & libraries', votes: 640 },
    ]
  });

  const stats = ref({
    services_count: 3,
    programs_count: 2,
    news_count: 3,
    subcities_count: 4,
    population: '230,000+',
    elevation: '1,842 m (6,043 ft)',
  });

  // Getter for current UI dictionary strings
  const t = computed(() => {
    return messages[locale.value] || messages.en;
  });

  // Actions
  function setLocale(newLocale: SupportedLocale) {
    locale.value = newLocale;
    localStorage.setItem('city_portal_locale', newLocale);
    fetchInitData();
  }

  function toggleDarkMode() {
    isDark.value = !isDark.value;
    if (isDark.value) {
      document.documentElement.classList.add('dark');
      localStorage.setItem('city_portal_theme', 'dark');
    } else {
      document.documentElement.classList.remove('dark');
      localStorage.setItem('city_portal_theme', 'light');
    }
  }

  function openSearch() {
    isSearchOpen.value = true;
  }

  function closeSearch() {
    isSearchOpen.value = false;
  }

  async function fetchInitData() {
    isLoading.value = true;
    try {
      const data = await api.getInitData(locale.value);
      if (data) {
        if (data.site_info) siteInfo.value = { ...siteInfo.value, ...data.site_info };
        if (data.emergency_contacts && data.emergency_contacts.length > 0) emergencyContacts.value = data.emergency_contacts;
        if (data.menus?.mega_menu && data.menus.mega_menu.length > 0) megaMenu.value = data.menus.mega_menu;
        if (data.active_poll) activePoll.value = data.active_poll;
        if (data.stats) stats.value = { ...stats.value, ...data.stats };
      }

      // Also fetch sliders
      const sliders = await api.getHeroSliders(locale.value);
      if (sliders && sliders.length > 0) {
        heroSliders.value = sliders;
      }
    } catch {
      // Keep rich fallback data intact
    } finally {
      isLoading.value = false;
    }
  }

  return {
    locale,
    isDark,
    isSearchOpen,
    isLoading,
    siteInfo,
    emergencyContacts,
    megaMenu,
    heroSliders,
    activePoll,
    stats,
    t,
    setLocale,
    toggleDarkMode,
    openSearch,
    closeSearch,
    fetchInitData,
  };
});
