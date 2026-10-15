<script setup lang="ts">
import { ref, computed } from 'vue';
import { usePortalStore } from '../stores/portal';
import { api } from '../services/api';
import UiIcon from './UiIcon.vue';

const portalStore = usePortalStore();

const selectedOption = ref<number | null>(null);
const hasVoted = ref(false);
const isSubmitting = ref(false);
const errorMsg = ref('');

const poll = computed(() => portalStore.activePoll);

const totalVotes = computed(() => {
  if (!poll.value) return 0;
  return poll.value.options.reduce((acc, opt) => acc + opt.votes, 0);
});

function calculatePercentage(votes: number): number {
  if (totalVotes.value === 0) return 0;
  return Math.round((votes / totalVotes.value) * 100);
}

async function handleVote() {
  if (!selectedOption.value || !poll.value) return;

  isSubmitting.value = true;
  errorMsg.value = '';

  try {
    const res = await api.votePoll(poll.value.id, selectedOption.value);
    if (res?.success) {
      const opt = poll.value.options.find(o => o.id === selectedOption.value);
      if (opt) opt.votes++;
      hasVoted.value = true;
    } else {
      errorMsg.value = res?.message || 'Failed to register vote.';
    }
  } catch (err: any) {
    if (err?.response?.status === 429) {
      errorMsg.value = 'You have already submitted a vote for this poll.';
      hasVoted.value = true;
    } else {
      errorMsg.value = err?.response?.data?.message || 'Network error submitting vote. Please try again.';
    }
  } finally {
    isSubmitting.value = false;
  }
}
</script>

<template>
  <div v-if="poll" class="civic-card p-6 md:p-8 bg-gradient-to-br from-white to-emerald-50/40 dark:from-slate-900 dark:to-slate-900/90 border-emerald-200/50 dark:border-emerald-900/40">
    <div class="flex items-center gap-2 mb-3">
      <span class="badge badge-emerald">
        <UiIcon name="check-circle" :size="12" />
        {{ portalStore.t.civicPoll }}
      </span>
      <span class="text-xs text-slate-500 font-medium">
        {{ totalVotes.toLocaleString() }} {{ portalStore.t.citizensVoted }}
      </span>
    </div>

    <h3 class="text-lg md:text-xl font-bold text-slate-900 dark:text-white mb-2 leading-snug">
      {{ poll.question }}
    </h3>
    <p v-if="poll.description" class="text-xs text-slate-500 dark:text-slate-400 mb-6">
      {{ poll.description }}
    </p>

    <!-- Vote Options (Before or After Voting) -->
    <div class="space-y-3">
      <div
        v-for="opt in poll.options"
        :key="opt.id"
        class="relative overflow-hidden rounded-xl border transition-all"
        :class="[
          selectedOption === opt.id && !hasVoted
            ? 'border-emerald-500 bg-emerald-50/50 dark:bg-emerald-950/30'
            : 'border-slate-200 dark:border-slate-800 bg-white/70 dark:bg-slate-800/40',
          !hasVoted ? 'cursor-pointer hover:border-emerald-300' : ''
        ]"
        @click="!hasVoted && (selectedOption = opt.id)"
      >
        <!-- Background Percentage Progress Bar (Visible after voting) -->
        <div
          v-if="hasVoted"
          class="absolute inset-y-0 left-0 bg-emerald-500/15 dark:bg-emerald-500/25 transition-all duration-1000"
          :style="{ width: `${calculatePercentage(opt.votes)}%` }"
        ></div>

        <div class="relative z-10 p-3.5 flex items-center justify-between text-xs md:text-sm font-medium">
          <div class="flex items-center gap-3">
            <span
              v-if="!hasVoted"
              class="w-4 h-4 rounded-full border flex items-center justify-center transition-colors"
              :class="selectedOption === opt.id ? 'border-emerald-600 bg-emerald-600' : 'border-slate-400'"
            >
              <span v-if="selectedOption === opt.id" class="w-1.5 h-1.5 rounded-full bg-white"></span>
            </span>
            <span class="text-slate-800 dark:text-slate-200">{{ opt.text }}</span>
          </div>

          <div v-if="hasVoted" class="font-bold text-emerald-600 dark:text-emerald-400 text-xs">
            {{ calculatePercentage(opt.votes) }}%
            <span class="text-[11px] text-slate-400 font-normal">({{ opt.votes }})</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Error Message Display -->
    <div v-if="errorMsg" class="mt-4 p-3 rounded-xl bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-900 text-red-700 dark:text-red-300 text-xs flex items-center gap-2">
      <UiIcon name="alert-circle" :size="16" />
      <span>{{ errorMsg }}</span>
    </div>

    <!-- Submit Vote Button or Success Message -->
    <div class="mt-6 flex items-center justify-between">
      <button
        v-if="!hasVoted"
        @click="handleVote"
        :disabled="!selectedOption || isSubmitting"
        class="btn btn-primary text-xs px-5 py-2.5 rounded-lg disabled:opacity-50 disabled:cursor-not-allowed"
      >
        <span v-if="isSubmitting">{{ portalStore.t.submitting }}</span>
        <span v-else>{{ portalStore.t.voteNow }}</span>
      </button>

      <div v-else class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 flex items-center gap-1.5">
        <UiIcon name="check" :size="16" />
        <span>{{ portalStore.t.votedSuccess }}</span>
      </div>

      <span class="text-[11px] text-slate-400">
        {{ portalStore.t.anonymousCivicBallot }}
      </span>
    </div>
  </div>
</template>
