<script setup>
import { ref, computed } from 'vue'
import { useFormatCurrency } from "@/composables/useFormatCurrency";
import { useDateFormat } from '@vueuse/core';
import { BadgeDollarSign } from 'lucide-vue-next';

const { formatWithCommas } = useFormatCurrency()
const props = defineProps({
    jobs: Object,
})

const biggest_job = ref([])
const biggest_proposal = ref([])

biggest_job.value = props.jobs[0]
biggest_proposal.value = biggest_job.value.proposals[0]

const proposalAmount = (proposal) => proposal.scopes.reduce((a, b) => a + b.lines.reduce((c, d) => c + (d.price*d.quantity), 0), 0)

props.jobs.forEach(job => {
    job.proposals.forEach(proposal => {
        let proposal_total = proposalAmount(proposal)
        if(proposal_total > (biggest_proposal.value ? proposalAmount(biggest_proposal.value) : 0)) {
            biggest_job.value = job
            biggest_proposal.value = proposal
        }
    })
})

const project_amount = computed(() => biggest_proposal.value ? proposalAmount(biggest_proposal.value) : 0)

const job_year = computed(() => biggest_job.value?.created_at ? new Date(biggest_job.value.created_at).getFullYear() : '')

const job_date = computed(() => useDateFormat(biggest_job.value?.created_at, 'MMM DD, YYYY').value)

</script>

<template>
    <div class="flex h-full flex-col">

        <!-- Header -->
        <div class="flex items-center gap-3">
            <div
                class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border-2 border-black bg-white text-black shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] dark:border-white dark:bg-black/20 dark:text-white"
            >
                <BadgeDollarSign class="h-4 w-4" :stroke-width="2.5" />
            </div>
            <p class="font-mono text-[11px] font-bold uppercase tracking-widest text-black/40 dark:text-white/40">
                Largest proposal
            </p>
        </div>

        <!-- Headline amount -->
        <div class="mt-4 min-h-0 flex-1">
            <p class="font-mono text-2xl font-extrabold tabular-nums text-black dark:text-white">
                {{ formatWithCommas(project_amount, 'currency') }}
            </p>
            <p class="mt-1 text-sm font-medium text-black/50 dark:text-white/50">
                {{ biggest_proposal?.name }}
            </p>
        </div>

        <!-- Meta -->
        <div class="flex flex-wrap items-center gap-2 border-t-2 border-black/10 pt-3 dark:border-white/10">
            <span
                class="inline-flex items-center rounded-lg border-2 border-black bg-black px-2 py-0.5 font-mono text-[11px] font-bold tracking-wide text-white dark:border-white dark:bg-white dark:text-black"
            >
                D{{ job_year }}-{{ biggest_job.number }}
            </span>
            <span class="text-xs font-bold text-black/50 dark:text-white/50">
                {{ biggest_proposal?.scopes.length }} scope{{ biggest_proposal?.scopes.length === 1 ? '' : 's' }}
            </span>
            <span class="text-black/20 dark:text-white/20">•</span>
            <span class="text-xs font-bold text-black/50 dark:text-white/50">
                {{ job_date }}
            </span>
        </div>
    </div>
</template>