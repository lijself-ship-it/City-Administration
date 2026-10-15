<script setup lang="ts">
import { ref } from 'vue';
import UiIcon from '../components/UiIcon.vue';

const isMaintenanceMode = ref(false);

const systemModules = [
  { name: 'Menu Management', description: 'Mega Menu & Footer Menu items and hierarchy', count: 12 },
  { name: 'Hero Sliders', description: 'Carousel banner slides and localized CTAs', count: 3 },
  { name: 'Emergency Contacts', description: '24/7 hotline numbers and emergency routing', count: 4 },
  { name: 'Government Leadership', description: 'Mayor, Cabinet, Council, and Sub-Cities', count: 8 },
  { name: 'Citizen Services', description: 'Text services and interactive step procedures', count: 3 },
  { name: 'Media & Press Room', description: 'News articles, Events, Vacancies, and Tenders', count: 9 },
  { name: 'Tourism & Explore', description: 'Categories, destinations, and image sliders', count: 3 },
  { name: 'Programs & Mega Projects', description: 'Corridor development and smart city masterplans', count: 2 },
  { name: 'Directives & Resources', description: 'Directives, regulations, and downloadable PDFs', count: 2 },
  { name: 'Civic Polls & Forum', description: 'Citizen surveys, community discussions, and threads', count: 3 },
];

const mockSubmissions = ref([
  { id: 1, tracking: 'CIT-78X2A1', name: 'Almaz Bekele', subject: 'Permits & Urban Planning', date: 'Oct 14, 2026', status: 'new' },
  { id: 2, tracking: 'CIT-45B9K3', name: 'Dawit Mengistu', subject: 'Water & Electricity Utility', date: 'Oct 13, 2026', status: 'in-review' },
  { id: 3, tracking: 'CIT-19P4Q8', name: 'Fatuma Kemal', subject: 'Corridor & Road Infrastructure', date: 'Oct 12, 2026', status: 'resolved' },
]);
</script>

<template>
  <div class="admin-portal container section-spacing space-y-10">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-200 dark:border-slate-800 pb-6">
      <div>
        <span class="badge badge-gold">Municipal Administration Panel</span>
        <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white mt-1">
          City Portal Management
        </h1>
        <p class="text-xs md:text-sm text-slate-500">
          Super Admin Console for multilingual content, emergency banners, and citizen interactions.
        </p>
      </div>

      <div class="flex items-center gap-3">
        <button
          @click="isMaintenanceMode = !isMaintenanceMode"
          class="btn text-xs px-4 py-2 rounded-xl transition"
          :class="isMaintenanceMode ? 'bg-red-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200'"
        >
          <UiIcon name="shield" :size="14" />
          <span>{{ isMaintenanceMode ? 'Maintenance Mode: ON' : 'Maintenance Mode: OFF' }}</span>
        </button>
      </div>
    </div>

    <!-- Quick Stats Metric Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
      <div class="civic-card p-5 border-l-4 border-l-emerald-500">
        <span class="text-xs text-slate-500 font-medium">Active Languages</span>
        <div class="text-2xl font-black text-slate-900 dark:text-white mt-1">3</div>
        <span class="text-[11px] text-emerald-600">English, አማርኛ, Oromoo</span>
      </div>
      <div class="civic-card p-5 border-l-4 border-l-blue-500">
        <span class="text-xs text-slate-500 font-medium">Managed Modules</span>
        <div class="text-2xl font-black text-slate-900 dark:text-white mt-1">16</div>
        <span class="text-[11px] text-blue-600">Normalized Database Schema</span>
      </div>
      <div class="civic-card p-5 border-l-4 border-l-amber-500">
        <span class="text-xs text-slate-500 font-medium">Citizen Inquiries</span>
        <div class="text-2xl font-black text-slate-900 dark:text-white mt-1">42</div>
        <span class="text-[11px] text-amber-600">3 Pending Review</span>
      </div>
      <div class="civic-card p-5 border-l-4 border-l-purple-500">
        <span class="text-xs text-slate-500 font-medium">System Performance</span>
        <div class="text-2xl font-black text-slate-900 dark:text-white mt-1">100%</div>
        <span class="text-[11px] text-purple-600">All Microservices Healthy</span>
      </div>
    </div>

    <!-- System Modules Directory -->
    <div class="space-y-4">
      <h3 class="text-lg font-bold text-slate-900 dark:text-white">Active Municipal Modules</h3>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <div
          v-for="mod in systemModules"
          :key="mod.name"
          class="civic-card p-5 flex items-start justify-between gap-4"
        >
          <div>
            <h4 class="font-bold text-sm text-slate-900 dark:text-white">{{ mod.name }}</h4>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ mod.description }}</p>
          </div>
          <span class="badge badge-emerald shrink-0">{{ mod.count }} Items</span>
        </div>
      </div>
    </div>

    <!-- Recent Citizen Submissions Table -->
    <div class="civic-card p-6 space-y-4">
      <div class="flex items-center justify-between">
        <h3 class="text-base font-bold text-slate-900 dark:text-white">Recent Citizen Inquiries</h3>
        <span class="text-xs text-slate-400">Auto-updated via API</span>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
          <thead>
            <tr class="border-b border-slate-200 dark:border-slate-800 text-slate-400 font-bold uppercase tracking-wider text-[10px]">
              <th class="py-2.5 px-3">Tracking Code</th>
              <th class="py-2.5 px-3">Citizen</th>
              <th class="py-2.5 px-3">Department</th>
              <th class="py-2.5 px-3">Date</th>
              <th class="py-2.5 px-3">Status</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
            <tr v-for="sub in mockSubmissions" :key="sub.id" class="hover:bg-slate-50 dark:hover:bg-slate-800/40">
              <td class="py-3 px-3 font-mono font-bold text-emerald-600">{{ sub.tracking }}</td>
              <td class="py-3 px-3 font-medium text-slate-800 dark:text-slate-200">{{ sub.name }}</td>
              <td class="py-3 px-3 text-slate-500">{{ sub.subject }}</td>
              <td class="py-3 px-3 text-slate-400">{{ sub.date }}</td>
              <td class="py-3 px-3">
                <span class="badge" :class="sub.status === 'new' ? 'badge-blue' : sub.status === 'in-review' ? 'badge-gold' : 'badge-emerald'">
                  {{ sub.status }}
                </span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>
