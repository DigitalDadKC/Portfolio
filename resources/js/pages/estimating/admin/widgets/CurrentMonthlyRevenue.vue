<script setup>
import { computed } from 'vue';
import { useFormatCurrency } from "@/composables/useFormatCurrency";
import { CircleDollarSign, TrendingUp, TrendingDown } from "lucide-vue-next";

const { formatWithCommas } = useFormatCurrency()
const props = defineProps({
    jobs: Object,
})

const current_month_jobs = props.jobs.filter(job => new Date(job.created_at).getMonth() == new Date().getMonth() && new Date(job.created_at).getFullYear() == new Date().getFullYear())
const previous_month_jobs = props.jobs.filter(job => new Date(job.created_at).getMonth() == new Date().getMonth()-1 && new Date(job.created_at).getFullYear() == new Date().getFullYear())
const current_month_revenue = current_month_jobs.flatMap(job => job.proposals.reduce((a, b) => a + b.scopes.reduce((c, d) => c + d.lines.reduce((e, f) => e + (f.price*f.quantity), 0), 0), 0)).reduce((g, h) => h + g, 0)
const previous_month_revenue = previous_month_jobs.flatMap(job => job.proposals.reduce((a, b) => a + b.scopes.reduce((c, d) => c + d.lines.reduce((e, f) => e + (f.price*f.quantity), 0), 0), 0)).reduce((g, h) => h + g, 0)

// Guard against divide-by-zero when there was no revenue last month.
const percent_change = computed(() => {
    if (!previous_month_revenue) return null
    return (current_month_revenue - previous_month_revenue) / previous_month_revenue * 100
})
const trend_up = computed(() => (percent_change.value ?? 0) > 0)

</script>

<template>
    <div class="flex h-full flex-col">

        <!-- Header -->
        <div class="flex items-center gap-3">
            <div
                class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border-2 border-black bg-white text-black shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] dark:border-white dark:bg-black/20 dark:text-white"
            >
                <CircleDollarSign class="h-4 w-4" :stroke-width="2.5" />
            </div>
            <p class="font-mono text-[11px] font-bold uppercase tracking-widest text-black/40 dark:text-white/40">
                Monthly revenue
            </p>
        </div>

        <!-- Headline amount -->
        <div class="mt-4 min-h-0 flex-1">
            <p class="font-mono text-2xl font-extrabold tabular-nums text-black dark:text-white">
                {{ formatWithCommas(current_month_revenue, 'currency') }}
            </p>

            <div class="mt-2 flex items-center gap-2">
                <span
                    v-if="percent_change !== null"
                    class="inline-flex items-center gap-1 rounded-lg border-2 border-black px-2 py-0.5 text-xs font-bold"
                    :class="trend_up
                        ? 'bg-green-50 text-green-700 dark:border-white dark:bg-green-950/40 dark:text-green-400'
                        : 'bg-red-50 text-red-700 dark:border-white dark:bg-red-950/40 dark:text-red-400'"
                >
                    <component :is="trend_up ? TrendingUp : TrendingDown" class="h-3.5 w-3.5" :stroke-width="3" />
                    {{ formatWithCommas(Math.abs(percent_change), 'percent') }}
                </span>
                <span class="text-xs font-medium text-black/50 dark:text-white/50">vs last month</span>
            </div>
        </div>

        <!-- Previous month -->
        <div class="flex items-center justify-between border-t-2 border-black/10 pt-3 dark:border-white/10">
            <span class="font-mono text-[10px] font-bold uppercase tracking-widest text-black/40 dark:text-white/40">
                Previous month
            </span>
            <span class="text-sm font-bold text-black dark:text-white">
                {{ formatWithCommas(previous_month_revenue, 'currency') }}
            </span>
        </div>
    </div>
</template>