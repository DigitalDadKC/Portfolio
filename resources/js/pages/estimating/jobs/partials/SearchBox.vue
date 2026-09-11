<script setup lang="ts">
import { Input } from '@/components/ui/input';
import { Search, X } from 'lucide-vue-next';

const model = defineModel<string>({ default: '' })

const clearSearch = () => {
    model.value = ''
}
</script>

<template>
    <div class="relative w-full px-6">
        <Input
            v-model="model"
            id="search"
            type="text"
            placeholder="Search Job #, City or Address..."
            class="w-full bg-white pl-10 pr-10"
        />

        <!-- Search icon -->
        <span class="absolute start-0 inset-y-0 flex items-center justify-center px-8 pointer-events-none">
            <Search class="size-5" :stroke-width="2.5" />
        </span>

        <!-- Clear button -->
        <button
            v-if="model"
            type="button"
            aria-label="Clear search"
            class="absolute right-8 top-1/2 flex h-6 w-6 -translate-y-1/2 items-center justify-center rounded-md text-black/40 transition-colors hover:bg-black/5 hover:text-black dark:text-white/40 dark:hover:bg-white/10 dark:hover:text-white"
            @click="clearSearch"
        >
            <X class="size-4 cursor-pointer" :stroke-width="2.5"  />
        </button>
    </div>
</template>