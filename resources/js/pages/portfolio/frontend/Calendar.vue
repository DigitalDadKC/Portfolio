<script setup>
import { CalendarClock } from 'lucide-vue-next';

const openCalendly = () => {
    if (window.Calendly) {
        window.Calendly.initPopupWidget({ url: 'https://calendly.com/digitaldadkc' });
    }
}
</script>

<template>
    <div class="fixed bottom-6 right-6 z-50 sm:bottom-8 sm:right-8">
        <button
            @click="openCalendly"
            type="button"
            aria-label="Schedule a quick call"
            class="
                group
                inline-flex items-center gap-2
                rounded-xl
                border-2 border-black
                bg-accent
                px-4 py-3
                text-sm font-extrabold
                text-white
                shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]
                transition-[transform,box-shadow,background-color]
                duration-200
                hover:-translate-y-1
                hover:bg-black
                hover:shadow-[6px_6px_0px_0px_rgba(0,0,0,1)]
                active:translate-y-0
                active:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]
                dark:border-white
                dark:hover:bg-white
                dark:hover:text-black
                cursor-pointer
            "
        >
            <!-- attention pulse -->
            <span class="relative flex h-2.5 w-2.5">
                <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-white/70 group-hover:bg-black/30 dark:group-hover:bg-black/40"></span>
                <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-white group-hover:bg-black/70 dark:group-hover:bg-black"></span>
            </span>

            <CalendarClock class="h-4 w-4" :stroke-width="2.5" />

            <span class="hidden sm:inline">Schedule a quick call</span>
            <span class="sm:hidden">Book a call</span>
        </button>
    </div>
</template>