<script setup lang="ts">
import { Building } from 'lucide-vue-next'
import CompanyDrawer from '../drawers/CompanyDrawer.vue';

const props = defineProps({
    company: Object,
    states: Object,
})
</script>

<template>
    <div class="flex h-full flex-col">

        <div class="flex items-start justify-between gap-2">
            <div class="flex items-center gap-3">
                <div
                    class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border-2 border-black bg-white text-black shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] dark:border-white dark:bg-black/20 dark:text-white"
                >
                    <Building class="h-4 w-4" :stroke-width="2.5" />
                </div>
                <p class="font-mono text-[11px] font-bold uppercase tracking-widest text-black/40 dark:text-white/40">
                    Company
                </p>
            </div>

            <CompanyDrawer :company :states></CompanyDrawer>
        </div>

        <div class="mt-4 flex min-h-0 flex-1 flex-col justify-center gap-1">
            <p class="text-lg font-extrabold text-black dark:text-white">{{ props.company.name }}</p>
            <p class="text-sm font-medium text-black/50 dark:text-white/50">{{ props.company.address }}</p>
            <p class="text-sm font-medium text-black/50 dark:text-white/50">
                {{ props.company.city }}<span v-if="props.company.state?.state">, {{ props.company.state.state }}</span> {{ props.company.zip }}
            </p>
        </div>
    </div>
</template>