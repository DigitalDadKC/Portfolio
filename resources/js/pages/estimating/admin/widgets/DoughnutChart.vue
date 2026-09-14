<script setup>
import { useCharts } from '@/composables/useCharts';
import { Doughnut } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, LineElement, ArcElement, CategoryScale, LinearScale, PointElement } from 'chart.js';
import { PieChart } from 'lucide-vue-next';

const { colors, months } = useCharts()
ChartJS.register(Title, Tooltip, Legend, ArcElement, CategoryScale, LinearScale, PointElement)

const props = defineProps({
    users: Object,
    jobs: Object,
})

const doughnutMetadata = []
props.users.forEach(user => {
    doughnutMetadata.push(props.jobs.flatMap(job => job.proposals).filter(proposal => proposal.estimator.id == user.id).length)
})

const doughnutData = {
  labels: props.users.map(user => user.name),
  datasets: [{
    label: 'proposals',
    data: doughnutMetadata,
    backgroundColor: colors,
    hoverOffset: 8
  }]
}

// Title now lives in the card's own HTML header (below), so the chart itself
// no longer needs to render one — Chart.js's built-in title always drew in
// its own default font/color and never matched the rest of the app anyway
// (the previous config's `familY` typo meant the font option was silently
// ignored by Chart.js, which is case-sensitive about `family`).
const doughnutChartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
        position: 'right',
        labels: {
            boxWidth: 10,
            padding: 12,
            font: {
                size: 11,
                weight: 'bold'
            }
        }
    },
    title: {
        display: false
    }
  }
}

</script>

<template>
    <div class="flex h-full flex-col">

        <!-- Header -->
        <div class="flex items-center gap-3">
            <div
                class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border-2 border-black bg-white text-black shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] dark:border-white dark:bg-black/20 dark:text-white"
            >
                <PieChart class="h-4 w-4" :stroke-width="2.5" />
            </div>
            <p class="font-mono text-[11px] font-bold uppercase tracking-widest text-black/40 dark:text-white/40">
                Proposals by estimator
            </p>
        </div>

        <!-- Chart -->
        <div class="relative mt-4 min-h-0 flex-1">
            <Doughnut id="doughnut-chart" :data="doughnutData" :options="doughnutChartOptions" aria-label="Doughnut Chart Data" aria-describedby="doughnut-chart">Chart could not be loaded</Doughnut>
        </div>
    </div>
</template>