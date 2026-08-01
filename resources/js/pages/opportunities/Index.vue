<script setup lang="ts">
import { ref, computed } from 'vue'
import { router, Head } from '@inertiajs/vue3';
import { useDebounceFn } from '@vueuse/core';
import Label from '@/components/ui/label/Label.vue';
import GuestLayout from '@/layouts/GuestLayout.vue';
import FilterDropdown from '@/components/FilterDropdown.vue';
import { useFormatCurrency } from '@/composables/useFormatCurrency';
import { useDateFormat } from '@vueuse/core';
import { Accordion, AccordionContent, AccordionItem, AccordionTrigger } from '@/components/ui/accordion'

const { formatWithCommas } = useFormatCurrency()
const props = defineProps({
    states: Object,
    cities: Object,
    filters: Object,
    city: Object,
    results: Object,
})

console.log(props.results)

const filter = ref(null)
const filtered_results = computed(() => {
    if(!filter?.value) {
        return props.results?.opportunitiesData;
    }
    return props.results?.opportunitiesData.filter(r => r.baseType == filter?.value)
})

// const year = ref(props.filters?.year)
// const state = ref(props.filters?.state)
// const city = ref(props.filters?.city)

// const states = computed(() => {
//     return [{id: null, abbr: 'Select', state: 'Select State'}, ...props.states]
// })

// const cities = computed(() => {
//     return [{id: null, city: 'Select City'}, ...props.cities]
// })

// const reload = useDebounceFn(() => {
//     router.post(route('lodging.filter'), {
//         year: year.value,
//         state: state.value,
//         city: city.value,
//     }, {
//         only: ['cities', 'city', 'filters'],
//         preserveState: true,
//         preserveScroll: true,
//         replace: true,
//     })
// }, 300)

// const months = [
//     'Jan', 'Feb', 'Mar', 'Apr',
//     'May', 'Jun', 'Jul', 'Aug',
//     'Sep', 'Oct', 'Nov', 'Dec'
// ];

// const currentYear = new Date().getFullYear();

// const years = Array.from({ length: 3 }, (_, index) => ({
//     id: index + 1,
//     year: currentYear - index,
// }));

const filtered_list = ref([
    'Justification (J&A)',
    'Pre solicitation',
    'Award Notice',
    'Sources Sought',
    'Special Notice',
    'Solicitation',
    'Sale of Surplus Property',
    'Combined Synopsis/Solicitation',
    'Intent to Bundle Requirements (DoD-Funded)'
])

</script>

<template>
    <Head title="Get Opportunities Example" />

    <GuestLayout title="Sam.gov Get Opportunities API Example">
        <main class="flex justify-center p-4 h-auto py-20 md:px-10">
            <div class="grid auto-rows-min gap-16">
                <div class="grid grid-cols-2">
                    <div>
                        <p>{{ filtered_results.length }} records</p>
                        <ul>Parameters:
                            <li>NAICS code: 236220</li>
                            <li>Start Date: Jan 1, 2026</li>
                            <li>End Date: Jul 1, 2026</li>
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
                    <AccordionItem
                        v-for="result in filtered_results"
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
                <!-- <div class="flex flex-col">
                    {{ props.filters }}<br />
                    State is {{ state }}
                    <div class="p-2">
                        <Label>Year</Label>
                        <FilterDropdown v-model="year" :options="years" column="year" value="year" @update:model-value="reload()" />
                    </div>
                    <div v-for="(error, i) in $page.props.errors" :key="i">
                        <p class="text-red-500">{{ error }}</p>
                    </div>
                    <div class="p-2">
                        <Label>State</Label>
                        <FilterDropdown v-model="state" :options="states" column="state" value="id" @update:model-value="reload()" />
                    </div>
                    <div class="p-2" v-if="state">
                        <Label>City</Label>
                        <FilterDropdown v-model="city" :options="cities" column="city" value="city" @update:model-value="reload()" />
                    </div>
                    {{ props.city }}
                </div>
                <div v-if="props.filters.city">
                    <div class="flex flex-col py-4">
                        Meals: {{ formatWithCommas(props.city?.Meals, 'currency') }}<br />
                        <div>
                            <div v-for="month in months" :key="month">
                                {{ `Lodging (${month} ${year}) - ${formatWithCommas(props.city[month], 'currency')}` }}
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else>
                    No data
                </div> -->
            </div>
        </main>
    </GuestLayout>
</template>