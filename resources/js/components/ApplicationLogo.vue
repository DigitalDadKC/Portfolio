<script setup lang="ts">
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import { useScrolled } from '@/composables/useScrolled'

const props = withDefaults(
    defineProps<{
        tagline?: string
        scrollEffect?: boolean
    }>(),
    {
        tagline: undefined,
        scrollEffect: false,
    }
)

const { scrolled } = useScrolled()

// Only true when the parent opted in AND the page is scrolled
const framed = computed(() => props.scrollEffect && scrolled.value)
</script>

<template>
    <Link
        href="/"
        aria-label="DigitalDadKC home"
        class="group inline-flex min-w-0 items-center gap-3 rounded-xl
               outline-none
               focus-visible:ring-2 focus-visible:ring-sky-500
               focus-visible:ring-offset-2
               dark:focus-visible:ring-offset-slate-950"
    >
        <!-- Mark: frame fades in once scrolled (only when scrollEffect is on) -->
        <span
            class="flex h-10 w-10 shrink-0 items-center justify-center
                   rounded-xl border
                   transition-[translate,background-color,border-color,box-shadow]
                   duration-300 ease-out
                   group-hover:-translate-y-0.5
                   motion-reduce:transition-none
                   motion-reduce:group-hover:translate-y-0"
            :class="framed
                ? 'border-slate-300/80 bg-white shadow-sm ring-1 ring-slate-900/5 dark:border-slate-700 dark:bg-slate-900 dark:ring-white/5'
                : 'border-transparent bg-transparent shadow-none'"
        >
            <img
                src="/img/dad.svg"
                alt=""
                aria-hidden="true"
                class="h-7 w-7 object-contain"
            />
        </span>

        <!-- Wordmark + optional tagline, stacked as one block -->
        <span class="flex min-w-0 flex-col leading-tight">
            <span
                class="whitespace-nowrap text-base font-bold tracking-tight
                       text-slate-900 transition-colors duration-200
                       group-hover:text-sky-600
                       dark:text-white dark:group-hover:text-sky-400
                       motion-reduce:transition-none"
            >
                Digital Dad
            </span>
            <span
                v-if="tagline"
                class="hidden truncate text-[11px] font-medium
                       text-slate-500 sm:block dark:text-slate-400"
            >
                {{ tagline }}
            </span>
        </span>
    </Link>
</template>