<script setup>
import { Link } from '@inertiajs/vue3';
import { useDateFormat } from '@vueuse/core';
import { NotepadText } from 'lucide-vue-next';

const props = defineProps({
    proposals: Object,
})

const formatDate = (date) => useDateFormat(date, 'M/DD/YYYY').value

</script>

<template>
    <div class="flex h-full flex-col">

        <!-- Header -->
        <div class="flex items-center gap-3">
            <div
                class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border-2 border-black bg-white text-black shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] dark:border-white dark:bg-black/20 dark:text-white"
            >
                <NotepadText class="h-4 w-4" :stroke-width="2.5" />
            </div>
            <p class="font-mono text-[11px] font-bold uppercase tracking-widest text-black/40 dark:text-white/40">
                Recent proposals
            </p>
        </div>

        <!-- List -->
        <div class="mt-3 flex min-h-0 flex-1 flex-col gap-1 overflow-y-auto">
            <p
                v-if="!props.proposals.length"
                class="rounded-xl border-2 border-dashed border-black/20 p-4 text-center text-xs font-bold text-black/30 dark:border-white/20 dark:text-white/30"
            >
                No proposals yet
            </p>

            <Link
                v-for="(item, index) in props.proposals.slice(0, 5)" :key="index"
                :href="route('proposals.edit', item.id)" prefetch
                class="flex items-center justify-between gap-3 rounded-xl border-2 border-black bg-white px-2.5 py-1 text-xs transition-[transform,background-color] duration-200 hover:-translate-y-0.5 hover:bg-light-quatrenary dark:border-white dark:bg-black/20 dark:hover:bg-dark-tertiary"
            >
                <span class="min-w-0 truncate font-bold text-black dark:text-white">
                    {{ item.name }}
                    <span class="font-medium text-black/40 dark:text-white/40">({{ item.type }})</span>
                </span>
                <span class="shrink-0 font-mono text-[11px] font-bold text-black/40 dark:text-white/40">
                    {{ formatDate(item.created_at) }}
                </span>
            </Link>
        </div>
    </div>
</template>