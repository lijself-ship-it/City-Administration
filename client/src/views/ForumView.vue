<script setup lang="ts">
import { ref, onMounted, watch } from 'vue';
import { usePortalStore } from '../stores/portal';
import { api } from '../services/api';
import UiIcon from '../components/UiIcon.vue';
import type { ForumTopicItem } from '../types';

const portalStore = usePortalStore();

const topics = ref<ForumTopicItem[]>([
  {
    id: 1,
    slug: 'pedestrian-safety-and-smart-crossings',
    title: 'Ideas for expanding raised pedestrian crossings near primary schools',
    content: 'With the corridor development accelerating, creating illuminated raised crossings near schools significantly enhances pupil safety. Local woredas should survey primary school crossings.',
    author: 'Abebe T. (Citizen)',
    views_count: 312,
    replies_count: 4,
    is_pinned: true,
    created_at: '2 days ago',
  },
  {
    id: 2,
    slug: 'neighborhood-waste-recycling-bins',
    title: 'Community segregated recycling bins at sub-city transit hubs',
    content: 'Proposing color-coded sorting bins for plastic bottles, organic kitchen waste, and paper at light rail stops.',
    author: 'Fatuma K. (Citizen)',
    views_count: 185,
    replies_count: 2,
    is_pinned: false,
    created_at: '4 days ago',
  }
]);

const isNewTopicModalOpen = ref(false);
const newTitle = ref('');
const newContent = ref('');
const isSubmitting = ref(false);

const activeThread = ref<ForumTopicItem | null>(null);
const replyText = ref('');
const threadReplies = ref<{ id: number; author: string; content: string; time: string }[]>([
  { id: 1, author: 'Municipal Transport Bureau', content: 'Thank you for this constructive recommendation. The 2026 pedestrian safety package includes 65 raised crosswalks near school zones.', time: '1 day ago' },
  { id: 2, author: 'Dawit M. (Kirkos Resident)', content: 'Fully agree, speed bumps and flashing warning amber lights would be very effective on corridor intersections.', time: '18 hours ago' }
]);

async function loadForum() {
  try {
    const data = await api.getForumTopics(portalStore.locale);
    if (data && data.length > 0) {
      const allTopics = data.flatMap((c: any) => c.topics || []);
      if (allTopics.length > 0) topics.value = allTopics;
    }
  } catch {
    // Keep defaults
  }
}

async function handleCreateTopic() {
  if (!newTitle.value || !newContent.value) return;
  isSubmitting.value = true;
  try {
    await api.createForumTopic(1, newTitle.value, newContent.value, portalStore.locale);
    topics.value.unshift({
      id: Date.now(),
      slug: 'citizen-topic-' + Date.now(),
      title: newTitle.value,
      content: newContent.value,
      author: 'You (Citizen)',
      views_count: 1,
      replies_count: 0,
      is_pinned: false,
      created_at: 'Just now',
    });
    isNewTopicModalOpen.value = false;
    newTitle.value = '';
    newContent.value = '';
  } catch {
    topics.value.unshift({
      id: Date.now(),
      slug: 'citizen-topic-' + Date.now(),
      title: newTitle.value,
      content: newContent.value,
      author: 'You (Citizen)',
      views_count: 1,
      replies_count: 0,
      is_pinned: false,
      created_at: 'Just now',
    });
    isNewTopicModalOpen.value = false;
    newTitle.value = '';
    newContent.value = '';
  } finally {
    isSubmitting.value = false;
  }
}

function handleAddReply() {
  if (!replyText.value.trim()) return;
  threadReplies.value.push({
    id: Date.now(),
    author: 'You (Citizen)',
    content: replyText.value,
    time: 'Just now'
  });
  if (activeThread.value) activeThread.value.replies_count++;
  replyText.value = '';
}

onMounted(() => {
  loadForum();
});

watch(() => portalStore.locale, () => {
  loadForum();
});
</script>

<template>
  <div class="forum-portal container section-spacing space-y-10">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
      <div class="max-w-2xl space-y-2">
        <span class="badge badge-emerald">{{ portalStore.t.civicCommunity }}</span>
        <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 dark:text-white">
          {{ portalStore.t.forumTitleFull }}
        </h1>
        <p class="text-sm text-slate-500 dark:text-slate-400">
          {{ portalStore.t.forumSubtitleFull }}
        </p>
      </div>

      <button
        @click="isNewTopicModalOpen = true"
        class="btn btn-primary text-xs px-5 py-2.5 rounded-xl self-start md:self-auto"
      >
        <UiIcon name="message-square" :size="16" />
        <span>{{ portalStore.t.newTopic }}</span>
      </button>
    </div>

    <!-- Topics List -->
    <div class="space-y-4">
      <div
        v-for="topic in topics"
        :key="topic.id"
        class="civic-card p-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-6 hover:border-emerald-500 cursor-pointer transition"
        @click="activeThread = topic"
      >
        <div class="space-y-2 max-w-2xl">
          <div class="flex items-center gap-2">
            <span v-if="topic.is_pinned" class="badge badge-gold">
              {{ portalStore.t.featured }}
            </span>
            <span class="text-xs font-semibold text-slate-400">
              Posted by {{ topic.author }} • {{ topic.created_at }}
            </span>
          </div>
          <h3 class="font-bold text-base md:text-lg text-slate-900 dark:text-white leading-snug">
            {{ topic.title }}
          </h3>
          <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2">
            {{ topic.content }}
          </p>
        </div>

        <div class="flex items-center gap-4 text-xs font-semibold text-slate-500 shrink-0">
          <span class="flex items-center gap-1.5 bg-slate-100 dark:bg-slate-800 px-3 py-1.5 rounded-lg">
            <UiIcon name="message-square" :size="14" class="text-emerald-600" />
            {{ topic.replies_count }} {{ portalStore.t.replies }}
          </span>
          <span class="text-slate-400 font-normal">{{ topic.views_count }} {{ portalStore.t.views }}</span>
        </div>
      </div>
    </div>

    <!-- Thread Details Modal -->
    <transition name="modal-fade">
      <div
        v-if="activeThread"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-md"
        @click.self="activeThread = null"
      >
        <div class="w-full max-w-2xl max-h-[90vh] overflow-y-auto bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-800 p-6 md:p-8 space-y-6">
          <div class="flex items-start justify-between border-b border-slate-200 dark:border-slate-800 pb-3">
            <div>
              <span class="text-xs text-slate-400 font-semibold">{{ activeThread.author }} • {{ activeThread.created_at }}</span>
              <h2 class="text-xl font-bold text-slate-900 dark:text-white mt-1">{{ activeThread.title }}</h2>
            </div>
            <button @click="activeThread = null" class="p-1 text-slate-400 hover:text-slate-600">
              <UiIcon name="x" :size="18" />
            </button>
          </div>

          <p class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed bg-slate-50 dark:bg-slate-800/40 p-4 rounded-xl border border-slate-200 dark:border-slate-800">
            {{ activeThread.content }}
          </p>

          <!-- Replies Section -->
          <div class="space-y-3">
            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">
              {{ portalStore.t.replies }} ({{ threadReplies.length }})
            </h4>
            <div
              v-for="reply in threadReplies"
              :key="reply.id"
              class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200/80 dark:border-slate-800 space-y-1 text-xs"
            >
              <div class="flex items-center justify-between font-bold text-slate-800 dark:text-slate-200">
                <span>{{ reply.author }}</span>
                <span class="font-normal text-slate-400 text-[11px]">{{ reply.time }}</span>
              </div>
              <p class="text-slate-600 dark:text-slate-300">{{ reply.content }}</p>
            </div>
          </div>

          <!-- Add Reply Form -->
          <div class="pt-2 flex gap-2">
            <input
              v-model="replyText"
              type="text"
              :placeholder="portalStore.t.yourComment"
              class="w-full px-3 py-2 text-xs bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:border-emerald-500"
              @keyup.enter="handleAddReply"
            />
            <button @click="handleAddReply" class="btn btn-primary text-xs px-4 py-2 rounded-xl">
              {{ portalStore.t.postReply }}
            </button>
          </div>
        </div>
      </div>
    </transition>

    <!-- New Topic Modal -->
    <transition name="modal-fade">
      <div
        v-if="isNewTopicModalOpen"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-md"
        @click.self="isNewTopicModalOpen = false"
      >
        <div class="w-full max-w-lg bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-800 p-6 md:p-8 space-y-4">
          <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-3">
            <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ portalStore.t.newTopic }}</h3>
            <button @click="isNewTopicModalOpen = false" class="p-1 text-slate-400">
              <UiIcon name="x" :size="18" />
            </button>
          </div>

          <form @submit.prevent="handleCreateTopic" class="space-y-4">
            <div>
              <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">{{ portalStore.t.subject }}</label>
              <input
                v-model="newTitle"
                type="text"
                :placeholder="portalStore.t.topicTitlePlaceholder"
                required
                class="w-full px-3 py-2 text-xs bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:border-emerald-500"
              />
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">{{ portalStore.t.message }}</label>
              <textarea
                v-model="newContent"
                rows="4"
                :placeholder="portalStore.t.topicContentPlaceholder"
                required
                class="w-full px-3 py-2 text-xs bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:border-emerald-500"
              ></textarea>
            </div>
            <div class="flex justify-end gap-2 pt-2">
              <button type="button" @click="isNewTopicModalOpen = false" class="btn btn-secondary text-xs px-4 py-2">
                {{ portalStore.t.close }}
              </button>
              <button type="submit" :disabled="isSubmitting" class="btn btn-primary text-xs px-5 py-2">
                {{ isSubmitting ? portalStore.t.submitting : portalStore.t.newTopic }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </transition>
  </div>
</template>
