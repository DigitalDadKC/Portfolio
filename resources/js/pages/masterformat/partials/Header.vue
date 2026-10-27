<script setup lang="ts">
import { Boxes } from "lucide-vue-next";

const props = defineProps({
    divisions: Object,
    sections: Object,
    subsections: Object,
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
                REFERENCE LIBRARY
            </div>

            <div>
                <h1
                    class="text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl dark:text-white"
                >
                    MasterFormat
                    <span
                        class="text-indigo-600 dark:text-indigo-400"
                    >
                        Explorer
                    </span>
                </h1>

                <p
                    class="mt-2 max-w-2xl text-sm leading-6 text-slate-500 sm:text-base dark:text-slate-400"
                >
                    Navigate the CSI construction classification
                    system, from divisions to individual
                    subsections.
                </p>
            </div>
        </div>

        <div
            class="flex w-fit items-center gap-3 rounded-2xl border border-light-quatrenary bg-light-secondary px-4 py-3 shadow-sm dark:border-dark-quatrenary dark:bg-dark-secondary"
        >
            <div
                class="flex size-10 items-center justify-center rounded-xl bg-indigo-100 text-indigo-700 dark:bg-indigo-400/15 dark:text-indigo-300"
            >
                <Boxes class="size-5" />
            </div>
            <div>
                <p
                    class="text-xs font-medium text-slate-500 dark:text-slate-400"
                >
                    Total classifications
                </p>
                <p
                    class="text-xl font-bold tracking-tight text-slate-900 dark:text-white"
                >
                    {{
                        (
                            props.divisions?.length +
                            props.sections?.length +
                            props.subsections?.length
                        ).toLocaleString()
                    }}
                </p>
            </div>
        </div>
    </header>
</template>