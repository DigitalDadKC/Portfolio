<script setup>
import {  } from 'vue';
import { Link, Head } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import EstimatingLayout from '@/Layouts/EstimatingLayout.vue';
import { Bar } from 'vue-chartjs';
import { Line } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, PointElement, LineElement } from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, PointElement, LineElement)

const props = defineProps({
    'jobs': Object
})

const labels = [
    'January',
    'February',
    'March',
    'April',
    'May',
    'June',
    'July',
    'August',
    'September',
    'October',
    'November',
    'December'
]

const barMetadata = props.jobs.map(job => labels[new Date(job.created_at).getMonth()]).reduce((a, b) => { a[b] = (a[b] || 0) + 1; return a; }, {})
const lineMetadata = []
labels.forEach((label, index) => {
  lineMetadata[index] = props.jobs.filter(job => new Date(job.created_at).getMonth() == index).map(job => job.scopes.map(scope => scope.lines.map(line => line.price * line.quantity)).map(total => total.reduce((a, b) => a+b, 0)).reduce((c, d) => c + d, 0)).reduce((e, f) => e+f, 0)
})

const barData = {
    labels: labels,
    datasets: [{
      label: 'Number of jobs',
      backgroundColor: '#B0A695',
      borderColor: '#152A38',
      data: barMetadata}]
}

const lineData = {
    labels: labels,
    datasets: [{
      label: 'Total Monthly Job $',
      backgroundColor: '#EBE3D5',
      borderColor: '#F3EEEA',
      data: lineMetadata}]
}

const chartOptions = {
  responsive: true,
}

</script>

<template>
    <EstimatingLayout>
        <Head title="Reporting" />

        <div class="bg-dark-primary h-screen pt-12">
            <div class="xl:container xl:mx-auto xl:p-0 w-screen">
                <div class="flex flex-col xl:flex-row gap-4 justify-center items-center p-2 md:px-12">
                    <div class="relative w-11/12">
                        <Bar id="bar-chart" :options="chartOptions" :data="barData" aria-label="Bar Chart Data" aria-describedby="bar-chart" class="bg-light-secondary rounded-md">Chart could not be loaded</Bar>
                    </div>
                    <div class="relative w-11/12">
                        <Line id="line-chart" :options="chartOptions" :data="lineData" aria-label="Line Chart Data" aria-describedby="line-chart" class="bg-light-tertiary">Chart could not be loaded</Line>
                    </div>
                </div>
            </div>
        </div>
    </EstimatingLayout>
</template>
