
<script setup lang="ts">
import { computed, ref, watch } from 'vue';
import FilterDropdown from '@/components/FilterDropdown.vue';
import GuestLayout from '@/layouts/GuestLayout.vue';
import { Landmark } from 'lucide-vue-next';

interface PerDiemRecord {
    State: string;
    City: string;
    [key: string]: string | number | null | undefined;
}

const props = withDefaults(
    defineProps<{ data?: PerDiemRecord[] }>(),
    { data: () => [] }
);

const state = ref<string | null>(null);
const city = ref<string | null>(null);

const months = [
    'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
    'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
];

const uniqueStates = computed(() =>
    [...new Set(props.data.map(item => item.State))]
        .filter(Boolean)
        .sort((a, b) => a.localeCompare(b))
);

const stateData = computed(() =>
    props.data.filter(item => item.State === state.value)
);

const cities = computed(() =>
    [...new Set(stateData.value.map(item => item.City))]
        .filter(Boolean)
        .sort((a, b) => {
            if (a === 'Standard Rate') return -1;
            if (b === 'Standard Rate') return 1;
            return a.localeCompare(b);
        })
);

const lodgingData = computed(() =>
    stateData.value.find(item => item.City === city.value) ?? null
);

const formatCurrency = (value: unknown) => {
    if (value === null || value === undefined || value === '') {
        return '—';
    }

    const amount = Number(value);

    if (!Number.isFinite(amount)) {
        return String(value);
    }

    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
        maximumFractionDigits: 2
    }).format(amount);
};

watch(state, () => {
    city.value = cities.value.includes('Standard Rate')
        ? 'Standard Rate'
        : (cities.value[0] ?? null);
});
</script>

<template>
    <GuestLayout title="Per Diem Explorer" :show-sidebar="true">
        <main class="mx-auto max-w-[1600px] space-y-8 px-4 py-8 sm:px-6 lg:px-10 lg:py-10">
            <div class="mx-auto max-w-7xl space-y-8">

                <!-- Page heading -->
                <header class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <div class="mb-3 inline-flex items-center gap-2 rounded-full border border-blue-100 bg-blue-50 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-blue-700">
                            <span class="h-2 w-2 rounded-full bg-blue-600"></span>
                            Government travel rates
                        </div>

                        <h1 class="text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">
                            Per Diem Explorer
                        </h1>

                        <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500 sm:text-base">
                            Explore monthly lodging per diem rates by state and city.
                        </p>
                    </div>
                </header>

                <!-- Filters -->
                <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
                    <div class="mb-5">
                        <h2 class="text-base font-semibold text-slate-900">
                            Find a lodging rate
                        </h2>
                        <p class="mt-1 text-sm text-slate-500">
                            Select a state, then choose a city or standard rate.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">
                                State
                            </label>
                            <FilterDropdown
                                v-model="state"
                                :options="uniqueStates"
                                width="48"
                                :show-all="false"
                            />
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">
                                City or rate area
                            </label>
                            <FilterDropdown
                                v-model="city"
                                :options="cities"
                                :show-all="false"
                            />
                            <p class="mt-2 text-xs text-slate-400">
                                Standard Rate appears first when available.
                            </p>
                        </div>
                    </div>

                    <div
                        v-if="state && lodgingData"
                        class="mt-6 flex flex-wrap items-center gap-2 border-t border-slate-100 pt-5"
                    >
                        <span class="text-sm text-slate-500">Viewing rates for</span>
                        <span class="rounded-lg bg-blue-50 px-3 py-1.5 text-sm font-semibold text-blue-700">
                            {{ state }}
                        </span>
                        <span class="text-slate-300">/</span>
                        <span class="rounded-lg bg-slate-100 px-3 py-1.5 text-sm font-semibold text-slate-700">
                            {{ city }}
                        </span>
                    </div>
                </section>

                <!-- Monthly rates -->
                <section>
                    <div class="mb-5 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <h2 class="text-xl font-bold tracking-tight text-slate-950">
                                Monthly lodging rates
                            </h2>
                            <p class="mt-1 text-sm text-slate-500">
                                Rates are displayed in US dollars.
                            </p>
                        </div>

                        <span
                            v-if="lodgingData"
                            class="text-sm font-medium text-slate-500"
                        >
                            {{ months.length }} months
                        </span>
                    </div>

                    <div
                        v-if="lodgingData"
                        class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6"
                    >
                        <article
                            v-for="(month, index) in months"
                            :key="month"
                            class="group rounded-xl border border-slate-200 bg-white p-4 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-md sm:p-5"
                        >
                            <div class="mb-4 flex items-center justify-between">
                                <span class="text-sm font-semibold text-slate-500">
                                    {{ month }}
                                </span>

                                <span
                                    v-if="index === new Date().getMonth()"
                                    class="h-2 w-2 rounded-full bg-blue-500"
                                    title="Current calendar month"
                                ></span>
                            </div>

                            <p class="text-xl font-bold tracking-tight text-slate-900 sm:text-2xl">
                                {{ formatCurrency(lodgingData[month]) }}
                            </p>

                            <div class="mt-3 h-1 w-full overflow-hidden rounded-full bg-slate-100">
                                <div
                                    class="h-full rounded-full bg-blue-500 transition-all group-hover:bg-blue-600"
                                    :style="{
                                        width: lodgingData[month] != null &&
                                            Number.isFinite(Number(lodgingData[month])) &&
                                            Number(lodgingData[month]) > 0
                                            ? `${Math.min(Number(lodgingData[month]) / 500 * 100, 100)}%`
                                            : '0%'
                                    }"
                                ></div>
                            </div>
                        </article>
                    </div>

                    <!-- Empty state -->
                    <div
                        v-else
                        class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center"
                    >
                        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="28"
                                height="28"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.7"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                aria-hidden="true"
                            >
                                <circle cx="11" cy="11" r="8" />
                                <path d="m21 21-4.35-4.35" />
                            </svg>
                        </div>

                        <h3 class="text-lg font-semibold text-slate-900">
                            Select a location to get started
                        </h3>
                        <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">
                            Choose a state above to explore its available cities and monthly lodging per diem rates.
                        </p>
                    </div>
                </section>

                <footer class="border-t border-light-secondary pt-5 text-xs leading-5">
                    Monthly lodging rates sourced from the data provided to this application.
                    Verify applicable rates with the official source before making travel arrangements.
                </footer>
            </div>
        </main>
    </GuestLayout>
</template>
