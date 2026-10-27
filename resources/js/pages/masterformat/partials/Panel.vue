<script setup lang="ts">
import { Layers3 } from "lucide-vue-next";

const props = defineProps({
    name: String,
    items: Object,
    div: Object,
    section: Object,
})

</script>

<template>
        <section
            class="overflow-hidden rounded-2xl border border-light-quatrenary bg-light-secondary shadow-sm dark:border-dark-quatrenary dark:bg-dark-secondary"
        >
            <header
                class="flex items-center justify-between border-b border-light-quatrenary bg-light-primary px-4 py-4 dark:border-dark-quatrenary dark:bg-dark-primary"
            >
                <div class="flex items-center gap-3">
                    <div
                        class="flex size-10 items-center justify-center rounded-xl bg-indigo-100 text-indigo-700 dark:bg-indigo-400/15 dark:text-indigo-300"
                    >
                        <Layers3 class="size-5" />
                    </div>
                    <div>
                        <h3
                            class="text-sm font-bold text-slate-900 dark:text-white"
                        >
                            {{ props.name }}
                        </h3>
                        <p
                            class="mt-0.5 text-xs text-slate-500 dark:text-slate-400"
                            v-if="props.div"
                        >
                            {{ `${props.div.code} ${props.div.name}` }}
                        </p>
                        <p
                            class="mt-0.5 text-xs text-slate-500 dark:text-slate-400"
                            v-else-if="props.section"
                        >
                            {{ `${props.section.code} ${props.section.name}` }}
                        </p>
                        <p
                            class="mt-0.5 text-xs text-slate-500 dark:text-slate-400"
                            v-else
                        >
                            Top-level categories
                        </p>
                    </div>
                </div>

                <span
                    class="rounded-lg border border-light-quatrenary bg-light-secondary px-2.5 py-1 font-mono text-xs font-semibold text-slate-500 dark:border-dark-quatrenary dark:bg-dark-secondary dark:text-slate-400"
                >
                    {{ props.items?.length }}
                </span>
            </header>

            <div
                class="h-[420px] space-y-1 overflow-y-auto p-2"
            >
                <slot />
                <div
                    v-if="!props.items?.length"
                    class="px-4 py-12 text-center text-sm text-slate-400"
                >
                    No {{ props.name }} available.
                </div>
            </div>
        </section>
</template>