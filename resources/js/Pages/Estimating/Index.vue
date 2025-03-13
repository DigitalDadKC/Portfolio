<script setup>
import { ref, watch } from 'vue';
import { Link, Head, router } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import EstimatingLayout from '@/Layouts/EstimatingLayout.vue';
import Paginator from '@/Components/Paginator.vue';
import { useDateFormat } from '@vueuse/core';

const props = defineProps({
    jobs: Object,
    filters: Object
})

const search = ref(props.filters.search)
const pages = ref(props.filters?.pages ?? 25)

const getJobs = () => {
    router.reload({
        data: {
            search: search.value,
            pages: pages.value
        },
        only: ['jobs', 'filters'],
        replace: true,
        preserveState: true
    })
}

watch(() => search.value, value => {
    getJobs()
})

</script>

<template>
    <EstimatingLayout>
        <Head title="Estimating" />

        <v-container fluid>
            <div>
                <div class="d-flex justify-between mb-2">
                    <div>
                        <Paginator :links="props.jobs.meta.links" />
                        <Link :href="route('estimating.create')">
                            <v-btn as="button" type="button">New Job</v-btn>
                        </Link>
                    </div>
                    Showing {{props.jobs.meta.from}} to {{props.jobs.meta.to}} of {{ props.jobs.meta.total }} Jobs
                    <div class="bg-grey pt-6 px-4 text-black rounded-lg">
                        <v-select density="compact" class="max-w-24" v-model="pages" variant="underlined" label="Pages" :items="[{'id': 10}, {'id': 25}, {'id': 50}, {'id': 100}]" item-title="id" item-value="id" @update:model-value="getJobs()"></v-select>
                    </div>
                </div>
                <v-text-field v-model="search" color="black" class="bg-grey-lighten-2" hide-details="auto" label="Search job #, address, or city..." density="compact"></v-text-field>
            </div>
            <v-table density="compact">
                <thead>
                    <tr>
                        <th>Job #</th>
                        <th>Address</th>
                        <th>City</th>
                        <th>State</th>
                        <th>Total</th>
                        <th>Actions</th>
                        <th>Created</th>
                        <th>Notes</th>
                        <th>PDF EXPORT</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(job, index) in props.jobs.data" :key="index">
                        <td>{{ job.number }}</td>
                        <td>{{ job.address }}</td>
                        <td>{{ job.city }}</td>
                        <td>{{ job.state.state }}</td>
                        <td>{{ $filters.formatCurrency(job.formatted_total) }}</td>
                        <td>
                            <Link :href="route('estimating.edit', {job: job})">
                                <v-btn as="button" type="button">Edit</v-btn>
                            </Link>
                        </td>
                        <td>{{ useDateFormat(job.created_at, 'M/D/YYYY') }}</td>
                        <td>{{ job.notes }}</td>
                        <td>
                            <a :href="route('downloadPDF.pdf', { job: job.id })">
                                <v-icon class="text-grey-darken-4">mdi-download</v-icon>
                            </a>
                        </td>
                        <td>
                            <a target="_blank" :href="route('browserPDF.pdf', { job: job.id })">
                                <v-icon class="text-grey-darken-4">mdi-file-document-arrow-right</v-icon>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </v-table>
        </v-container>
    </EstimatingLayout>
</template>
