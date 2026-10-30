<script setup lang="ts">
import { useStorage } from '@vueuse/core';
import { Notebook, BookMarked, DollarSign, House, Upload } from 'lucide-vue-next';

import { SidebarProvider, SidebarTrigger } from '@/components/ui/sidebar';

import AppSidebar from '@/components/AppSidebar.vue';
import ApplicationLogo from '@/components/ApplicationLogo.vue';
import DarkMode from '@/components/DarkMode.vue';
import { useScrolled } from '@/composables/useScrolled';

const props = defineProps({
    title: String,
    showSidebar: {
        type: Boolean,
        default: false,
    },
});

const isSidebarOpen = useStorage('sidebar-state', true);
const { scrolled } = useScrolled();

const currentDate = new Intl.DateTimeFormat('en-US', {
    weekday: 'long',
    month: 'long',
    day: 'numeric',
    year: 'numeric',
}).format(new Date());

const navigations = [
    { name: 'Construction Estimating', url: '/estimating/jobs', icon: Notebook },
    { name: 'Invoicing', url: '/invoices', icon: DollarSign },
    { name: 'CSI/Masterformat Reference', url: '/masterformat', icon: BookMarked },
    { name: 'Sam.gov API', url: '/lodging', icon: House },
    { name: 'Dropzone', url: '/dropzone', icon: Upload },
];
</script>

<template>
    <div
        class="flex h-dvh flex-col overflow-hidden
               bg-slate-50 text-slate-900
               dark:bg-slate-950 dark:text-slate-100"
    >
        <SidebarProvider
            :default-open="isSidebarOpen"
            style="--sidebar-width: 17rem; --sidebar-width-mobile: 18rem"
            class="min-h-0 flex-1 overflow-hidden"
        >
            <header
                class="fixed inset-x-0 top-0 z-40 h-16
                       border-b border-slate-200/80
                       bg-white/90 backdrop-blur-xl
                       transition-shadow duration-300
                       dark:border-slate-800 dark:bg-slate-950/90"
                :class="{ 'shadow-sm': scrolled }"
            >
                <div class="flex h-full items-center justify-between gap-4 px-4 sm:px-6">
                    <div class="flex min-w-0 items-center gap-3">
                        <SidebarTrigger
                            v-if="showSidebar"
                            class="shrink-0 rounded-lg text-slate-500
                                   hover:bg-slate-100 hover:text-slate-900
                                   dark:hover:bg-slate-800 dark:hover:text-white"
                            @click="isSidebarOpen = !isSidebarOpen"
                        />

                        <ApplicationLogo tagline="Developer portfolio" />

                        <template v-if="props.title">
                            <span
                                class="hidden h-6 w-px shrink-0 bg-slate-200
                                       sm:block dark:bg-slate-800"
                            />
                            <span
                                class="hidden truncate text-sm font-medium
                                       text-slate-500 sm:block
                                       dark:text-slate-400"
                            >
                                {{ props.title }}
                            </span>
                        </template>
                    </div>

                    <div class="flex shrink-0 items-center gap-2 sm:gap-3">
                        <template v-if="$slots.navigation">
                            <slot name="navigation" />
                            <span class="h-6 w-px bg-slate-200 dark:bg-slate-800" />
                        </template>

                        <!-- Current date -->
                        <div
                            class="hidden items-center rounded-lg border border-slate-200
                                bg-slate-50 px-3 py-1.5 text-xs font-medium
                                text-slate-600 sm:flex
                                dark:border-slate-800 dark:bg-slate-900
                                dark:text-slate-300"
                        >
                            <span>{{ currentDate }}</span>
                        </div>

                        <DarkMode />
                    </div>
                </div>
            </header>

            <AppSidebar
                v-if="showSidebar"
                :navigations="navigations"
            />

            <main class="flex min-h-0 min-w-0 flex-1 flex-col overflow-hidden pt-16">
                <div class="min-h-0 flex-1 overflow-y-auto overflow-x-hidden">
                    <div
                        v-motion-fade
                        class="min-h-screen bg-light-tertiary text-slate-800 rounded-md antialiased transition-colors duration-300 dark:bg-dark-tertiary dark:text-slate-100"
                    >
                        <slot />
                    </div>
                </div>

                <footer
                    class="mx-auto flex w-full shrink-0 max-w-[1600px]
                        flex-col gap-2 border-t border-slate-200/80
                        bg-slate-50 px-4 py-5 text-xs text-slate-500
                        sm:flex-row sm:items-center sm:justify-end
                        sm:px-6 lg:px-8
                        dark:border-slate-800 dark:bg-slate-950"
                >
                    <span>© {{ new Date().getFullYear() }} Digital Dad, LLC</span>
                </footer>

            </main>
        </SidebarProvider>
    </div>
</template>