<script setup lang="ts">
import { computed } from 'vue';
import { ChevronRight } from "lucide-vue-next";

const props = defineProps({
    div: Object,
    section: Object,
    subsection: Object,
})

const activePath = computed(() => [
    props.div, props.section, props.subsection,
].filter(Boolean).length);
</script>

<template>
    <section
        class="flex flex-col gap-3 rounded-2xl border border-light-quatrenary bg-light-secondary p-4 sm:flex-row sm:items-center sm:justify-between sm:px-5 dark:border-dark-quatrenary dark:bg-dark-secondary"
    >
        <div>
            <p
                class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500"
            >
                Current selection
            </p>

            <div
                class="mt-2 flex flex-wrap items-center gap-2 text-sm"
            >
                <span
                    v-if="div"
                    class="rounded-lg bg-indigo-100 px-2.5 py-1 font-mono text-xs font-semibold text-indigo-800 dark:bg-indigo-400/15 dark:text-indigo-300"
                >
                    {{ div.code }}
                </span>
                <span
                    v-if="div"
                    class="font-medium text-slate-700 dark:text-slate-200"
                >
                    {{ div.name }}
                </span>

                <ChevronRight
                    v-if="section"
                    class="size-3.5 text-slate-300"
                />

                <span
                    v-if="section"
                    class="rounded-lg bg-sky-100 px-2.5 py-1 font-mono text-xs font-semibold text-sky-800 dark:bg-sky-400/15 dark:text-sky-300"
                >
                    {{ section.code }}
                </span>
                <span
                    v-if="section"
                    class="font-medium text-slate-700 dark:text-slate-200"
                >
                    {{ section.name }}
                </span>

                <ChevronRight
                    v-if="subsection"
                    class="size-3.5 text-slate-300"
                />

                <span
                    v-if="subsection"
                    class="rounded-lg bg-emerald-100 px-2.5 py-1 font-mono text-xs font-semibold text-emerald-800 dark:bg-emerald-400/15 dark:text-emerald-300"
                >
                    {{ subsection.code }}
                </span>
                <span
                    v-if="subsection"
                    class="font-medium text-slate-700 dark:text-slate-200"
                >
                    {{ subsection.name }}
                </span>

                <span
                    v-if="!div"
                    class="text-slate-500 dark:text-slate-400"
                >
                    Select a division to get started.
                </span>
            </div>
        </div>

        <div
            class="flex shrink-0 items-center gap-2 self-start sm:self-center"
        >
            <span
                class="size-2 rounded-full bg-emerald-500"
            />
            <span
                class="text-xs font-medium text-slate-500 dark:text-slate-400"
            >
                {{ activePath }} of 3 levels selected
            </span>
        </div>
    </section>
</template>