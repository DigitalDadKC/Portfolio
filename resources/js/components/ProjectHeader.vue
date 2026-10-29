<script setup lang="ts">
import { Boxes } from "lucide-vue-next";

const props = defineProps({
    title: String,
    subtitle: String,
    total_category: String,
    total: Number,
    explorer: {
        type: Boolean,
        default: false,
    }
})
</script>

<template>
    <header
        class="flex flex-col justify-between gap-5 sm:flex-row sm:items-end"
    >
        <div class="space-y-3">
            
            <div
                class="inline-flex items-center gap-2 rounded-full border border-light-quatrenary bg-light-secondary px-3 py-1 text-xs font-semibold tracking-wide text-indigo-700 dark:border-dark-quatrenary dark:bg-dark-secondary dark:text-indigo-300"
            >
                <span class="size-1.5 rounded-full bg-indigo-500" />
                {{ props.subtitle }}
            </div>

            <div>
                <h1
                    class="text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl dark:text-white"
                >
                    {{ props.title }}
                    <span
                        class="text-indigo-600 dark:text-indigo-400"
                        v-if="props.explorer"
                    >
                        Explorer
                    </span>
                </h1>

                <p
                    class="mt-2 max-w-2xl text-sm leading-6 text-slate-500 sm:text-base dark:text-slate-400"
                >
                    <slot />
                </p>
            </div>
        </div>

        <div
            class="flex w-fit items-center gap-3 rounded-2xl border border-light-quatrenary bg-light-secondary px-4 py-3 shadow-sm dark:border-dark-quatrenary dark:bg-dark-secondary"
        >
            <div
                class="flex size-10 items-center justify-center rounded-xl bg-indigo-100 text-indigo-700 dark:bg-indigo-400/15 dark:text-indigo-300"
            >
                <slot name="icon" />
            </div>
            <div>
                <p
                    class="text-xs font-medium text-slate-500 dark:text-slate-400"
                >
                    {{ props.total_category }}
                </p>
                <p
                    class="text-xl font-bold tracking-tight text-slate-900 dark:text-white"
                >
                    {{ props.total }}
                </p>
            </div>
        </div>
    </header>
</template>