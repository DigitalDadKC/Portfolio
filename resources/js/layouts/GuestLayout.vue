<script setup lang="ts">
import { useStorage } from '@vueuse/core';
import { Notebook, BookMarked, DollarSign } from 'lucide-vue-next';

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

const navigations = [
    { name: 'Construction Estimating', url: '/estimating/jobs', icon: Notebook },
    { name: 'Invoicing', url: '/invoices', icon: DollarSign },
    { name: 'Masterformat', url: '/masterformat', icon: BookMarked },
];
</script>

<template>
    <div
        class="min-h-screen bg-slate-50 text-slate-900
               dark:bg-slate-950 dark:text-slate-100"
    >
        <SidebarProvider
            :default-open="isSidebarOpen"
            style="--sidebar-width: 17rem; --sidebar-width-mobile: 18rem"
            class="min-h-screen"
        >
            <!-- Application header -->
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

                    <!-- Right: page actions, then theme toggle -->
                    <div class="flex shrink-0 items-center gap-2 sm:gap-3">
                        <template v-if="$slots.navigation">
                            <slot name="navigation" />
                            <span class="h-6 w-px bg-slate-200 dark:bg-slate-800" />
                        </template>

                        <DarkMode />
                    </div>
                </div>
            </header>

            <!-- Project navigation -->
            <AppSidebar
                v-if="showSidebar"
                :navigations="navigations"
            />

            <!-- Main content -->
            <main class="min-h-screen w-full pt-16">
                <div
                    class="mx-auto w-full max-w-[1600px] px-4 py-6
                           sm:px-6 sm:py-8 lg:px-8 lg:py-10"
                >
                    <slot />
                </div>

                <footer
                    class="mx-auto flex max-w-[1600px] flex-col gap-2
                           border-t border-slate-200/80
                           px-4 py-5 text-xs text-slate-500
                           sm:flex-row sm:items-center sm:justify-between
                           sm:px-6 lg:px-8
                           dark:border-slate-800"
                >
                    <span>© {{ new Date().getFullYear() }} Digital Dad, LLC</span>
                </footer>
            </main>
        </SidebarProvider>
    </div>
</template>