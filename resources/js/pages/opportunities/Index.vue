<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { Head } from '@inertiajs/vue3';
import GuestLayout from '@/layouts/GuestLayout.vue';
import FilterDropdown from '@/components/FilterDropdown.vue';
import { useFormatCurrency } from '@/composables/useFormatCurrency';
import { useOffsetPagination, useDateFormat } from '@vueuse/core';
import { Accordion, AccordionContent, AccordionItem, AccordionTrigger } from '@/components/ui/accordion'

const { formatWithCommas } = useFormatCurrency()
const props = defineProps({
    states: Object,
    cities: Object,
    filters: Object,
    city: Object,
    results: Object,
})

const pageSize = 10
const pageSizes = [10, 25, 50, 100]

const filter = ref(null)
const filtered_results = computed(() => {
    if(!filter?.value) {
        return props.results?.opportunitiesData;
    }
    return props.results?.opportunitiesData.filter(r => r.baseType == filter?.value)
})

const { currentPage, currentPageSize, pageCount, isFirstPage, isLastPage, prev, next } = useOffsetPagination({
    total: computed(() => filtered_results.value.length),
    pageSize: 10,
})

const paginatedResults = computed(() => {
    const start = (currentPage.value - 1) * currentPageSize.value
    const end = start + currentPageSize.value

    return filtered_results.value.slice(start, end)
})

const filtered_list = ref([
    'Justification',
    'Presolicitation',
    'Award Notice',
    'Sources Sought',
    'Special Notice',
    'Solicitation',
    'Sale of Surplus Property',
    'Combined Synopsis/Solicitation',
    'Intent to Bundle Requirements (DoD-Funded)'
])

watch(
    () => filter.value,
    () => {
        currentPage.value = 1
    }
)

watch(
    currentPageSize,
    () => {
        currentPage.value = 1
    }
)

</script>

<template>
    <Head title="Get Opportunities Example" />

    <GuestLayout title="Sam.gov Get Opportunities API Example">
        <main class="flex justify-center p-4 h-auto py-20 md:px-10">
            <div class="grid w-full max-w-5xl auto-rows-min gap-16">
                <div class="grid grid-cols-2">
                    <div>
                        <p>{{ filtered_results.length }} records</p>
                        <ul>Parameters:
                            <li>NAICS code: 236220</li>
                            <li>Start Date: Jan 1, 2026</li>
                            <li>End Date: Aug 1, 2026</li>
                            <li>Results cached for 12 hours</li>
                        </ul>
                    </div>
                    <div>
                        <FilterDropdown v-model="filter" :options="filtered_list" />
                    </div>
                </div>
                <Accordion
                    type="multiple"
                    class="w-full space-y-2"
                >

                    <div class="flex flex-col items-center justify-between gap-4 pt-6 sm:flex-row">
                        <div class="flex items-center gap-2 text-sm">
                            <label for="page-size">
                                Show
                            </label>

                            <FilterDropdown
                                v-model="currentPageSize"
                                :options="pageSizes"
                                :show-all="false"
                                :return-number="true"
                                class="w-24"
                            />

                            <span>records</span>
                        </div>

                        <div class="flex items-center gap-4">
                            <button
                                type="button"
                                :disabled="isFirstPage"
                                @click="prev"
                                class="rounded-md border px-4 py-2 disabled:cursor-not-allowed disabled:opacity-40"
                            >
                                Previous
                            </button>

                            <span class="text-sm text-muted-foreground whitespace-nowrap">
                                Page {{ currentPage }} of {{ pageCount }}
                            </span>

                            <button
                                type="button"
                                :disabled="isLastPage"
                                @click="next"
                                class="rounded-md border px-4 py-2 disabled:cursor-not-allowed disabled:opacity-40"
                            >
                                Next
                            </button>
                        </div>
                    </div>

                    <AccordionItem
                        v-for="result in paginatedResults"
                        :key="result.noticeId"
                        :value="result.noticeId"
                        class="border rounded-lg px-4 bg-light-tertiary"
                    >
                        <AccordionTrigger class="text-left hover:no-underline">
                            <div class="flex flex-col items-start">
                                <span class="font-semibold">
                                    {{ result.title }}
                                </span>

                                <span class="text-sm text-muted-foreground">
                                    Solicitation #{{ result.solicitationNumber }}
                                    • {{ result.baseType }}
                                    • Posted
                                    {{ useDateFormat(result.postedDate, 'MMM D, YYYY') }}
                                </span>
                            </div>
                        </AccordionTrigger>

                        <AccordionContent>
                            <div class="space-y-4 py-2">

                                <div>
                                    <strong>Solicitation #</strong><br />
                                    {{ result.solicitationNumber }}
                                </div>

                                <div>
                                    <strong>Posted</strong><br />
                                    {{ useDateFormat(result.postedDate, 'MMM D, YYYY h:mm A') }}
                                </div>

                                <div v-if="result.responseDeadLine">
                                    <strong>Response Deadline</strong><br />
                                    {{ useDateFormat(result.responseDeadLine, 'MMM D, YYYY h:mm A') }}
                                </div>

                                <div>
                                    <strong>Active</strong><br />
                                    {{ result.active ? 'Yes' : 'No' }}
                                </div>

                                <div>
                                    <strong>Opportunity Type</strong><br />
                                    {{ result.baseType }}
                                </div>

                                <div v-if="Object.keys(result.award ?? {}).length">
                                    <strong>Award</strong><br />
                                    {{ formatWithCommas(result.award.amount, 'currency') }}
                                    <span v-if="result.award.awardee">
                                        ({{ result.award.awardee.name }})
                                    </span>
                                </div>

                                <div
                                    v-if="result.pointOfContact?.length"
                                >
                                    <strong>Contacts</strong>

                                    <div
                                        v-for="contact in result.pointOfContact"
                                        :key="contact.email"
                                        class="mt-3 rounded border p-3"
                                    >
                                        <div>{{ contact.fullName }}</div>

                                        <div v-if="contact.phone" class="text-sm text-muted-foreground">
                                            📞 {{ contact.phone }}
                                        </div>

                                        <div v-if="contact.email" class="text-sm">
                                            <a
                                                :href="`mailto:${contact.email}`"
                                                class="underline"
                                            >
                                                {{ contact.email }}
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <strong>SAM.gov</strong><br />
                                    <a
                                        :href="result.uiLink"
                                        target="_blank"
                                        class="text-blue-600 underline"
                                    >
                                        View Opportunity
                                    </a>
                                </div>

                            </div>
                        </AccordionContent>
                    </AccordionItem>
                </Accordion>
            </div>
        </main>
    </GuestLayout>
</template>