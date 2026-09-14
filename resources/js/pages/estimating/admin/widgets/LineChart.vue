<script setup>
import { useCharts } from '@/composables/useCharts';
import { Line } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, LineElement, ArcElement, CategoryScale, LinearScale, PointElement } from 'chart.js';
import { LineChart as LineChartIcon } from 'lucide-vue-next';

const { colors, months } = useCharts()
ChartJS.register(Title, Tooltip, Legend, LineElement, CategoryScale, LinearScale, PointElement)

const props = defineProps({
    jobs: Object,
})


const lineMetadata = []
const yearly_jobs = props.jobs.filter(job => new Date(job.created_at).getFullYear() == new Date().getFullYear())
months.forEach(month => {
    lineMetadata.push(yearly_jobs.filter(job => new Date(job.created_at).getMonth() == month.id).flatMap(job => job.proposals).length)
})

// A line's own color comes from `borderColor`, not `backgroundColor` — the
// previous config only set `backgroundColor` (to an array of several colors,
// which is meant for multi-slice charts like the doughnut, not a single
// line), so Chart.js fell back to its near-invisible default line color.
const lineData = {
    labels: months.map(item => item.month + ' ' + new Date().getFullYear()),
    datasets: [{
        label: 'Proposal Count',
        data: lineMetadata,
        borderColor: colors[0],
        backgroundColor: colors[0],
        pointBackgroundColor: colors[0],
        pointRadius: 3,
        borderWidth: 2,
        tension: 0.35,
    }]
}

// Title now lives in the card's own HTML header (below) instead of being
// drawn on the canvas — the previous config's `familY` typo (Chart.js's
// `family` option is case-sensitive) meant the font never actually applied.
const lineChartOptions = {
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
                <LineChartIcon class="h-4 w-4" :stroke-width="2.5" />
            </div>
            <p class="font-mono text-[11px] font-bold uppercase tracking-widest text-black/40 dark:text-white/40">
                Monthly proposal count
            </p>
        </div>

        <!-- Chart -->
        <div class="relative mt-4 min-h-0 flex-1">
            <Line id="line-chart" :data="lineData" :options="lineChartOptions" aria-label="Line Chart Data" aria-describedby="line-chart">Chart could not be loaded</Line>
        </div>
    </div>
</template>