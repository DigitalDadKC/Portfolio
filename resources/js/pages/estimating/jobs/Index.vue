<script setup lang="ts">
import { ref, watch } from "vue";
import { Link, router } from "@inertiajs/vue3";
import EstimatingLayout from "@/layouts/EstimatingLayout.vue";
import Paginator from "@/components/Paginator.vue";
import { Button } from "@/components/ui/button";
import SearchBox from "./partials/SearchBox.vue";
import Pages from "./partials/Pages.vue";
import ManageJob from "./modals/ManageJob.vue";
import Filters from "./partials/Filters.vue";
import StateTag from "./partials/StateTag.vue";
import CustomerTag from "./partials/CustomerTag.vue";
import { useDateFormat } from "@vueuse/core";
import { useFormatCurrency } from "@/composables/useFormatCurrency";
import { Download, FileText, Plus, ArrowUpRight, HardHat } from "lucide-vue-next";
import ProjectHeader from "@/components/ProjectHeader.vue";

const props = defineProps({
    jobs: Object,
    states: Object,
    customers: Object,
    filters: Object,
});

const { formatWithCommas } = useFormatCurrency();
const search = ref(props.filters.search);
const page = ref(1)
const pages = ref(props.filters.pages);
const state_ids = ref(props.filters.states ?? []);
const customer_ids = ref(props.filters.customers ?? []);

const reload = () => {
    router.post(
        route('jobs.filter'), {
            page: page.value,
            pages: pages.value,
            search: search.value,
            states: state_ids.value,
            customers: customer_ids.value,
        }, {
            only: ["jobs", "filters"],
            replace: true,
            preserveState: true,
            preserveScroll: true,
        },
    );
};

watch([page, pages, search, state_ids, customer_ids], () => {
        reload()
    }
);

const remove_state_filter = (id) => {
    state_ids.value = state_ids.value.filter((state_id) => state_id !== id);
};

const remove_customer_filter = (id) => {
    customer_ids.value = customer_ids.value.filter(
        (customer_id) => customer_id !== id,
    );
};

const stripePalette = [
    "bg-accent",
    "bg-light-quatrenary",
    "bg-light-tertiary",
    "bg-light-secondary",
];
const stripeClass = (job) =>
    stripePalette[(job.state?.id ?? job.id ?? 0) % stripePalette.length];

const proposalTotal = (proposal) =>
    proposal.scopes.reduce(
        (a, b) =>
            a +
            b.lines.reduce((c, d) => c + (d.price * d.quantity * 100) / 100, 0),
        0,
    );
</script>

<template>
    <EstimatingLayout>
        <div class="mx-auto w-full max-w-7xl px-4 py-8">

            <ProjectHeader
                title="Estimating"
                subtitle="Construction Software"
                :total="props.jobs.data.length"
                total_category="Jobs"
            >
                Create proposals with custom-built pricing tools
                <template #icon>
                    <HardHat class="size-5" />
                </template>
            </ProjectHeader>

            <div
                class="mb-5 flex flex-col gap-4 rounded-2xl border-2 border-black bg-light-primary p-4 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] dark:border-white dark:bg-dark-primary lg:flex-row lg:items-center lg:justify-between"
            >
                <SearchBox v-model="search" class="w-full lg:max-w-sm" />

                <div class="flex shrink-0 items-center gap-4">
                    <Filters
                        :states
                        :customers
                        v-model:selectedStates="state_ids"
                        v-model:selectedCustomers="customer_ids"
                    />

                    <div class="hidden h-8 w-px shrink-0 bg-black/10 dark:bg-white/10 lg:block"></div>

                    <div class="flex shrink-0 items-center gap-3 whitespace-nowrap text-sm font-bold text-black/60 dark:text-white/60">
                        <span class="font-mono tabular-nums">
                            {{ props.jobs.meta.from }}–{{ props.jobs.meta.to }}
                        </span>

                        <span>of</span>

                        <span class="font-mono tabular-nums">
                            {{ props.jobs.meta.total }}
                        </span>

                        <span>jobs</span>

                        <Pages v-model="pages" />
                    </div>
                </div>
            </div>

            <!-- Active filter chips -->
            <div
                v-if="state_ids.length || customer_ids.length"
                class="mb-5 flex flex-wrap items-center gap-2"
            >
                <template v-if="state_ids.length">
                    <span
                        class="font-mono text-[11px] font-bold uppercase tracking-widest text-black/40 dark:text-white/40"
                    >
                        States
                    </span>
                    <div v-for="filter in state_ids" :key="`state-${filter}`">
                        <StateTag
                            :filter
                            :states
                            @remove="(id) => remove_state_filter(id)"
                        />
                    </div>
                </template>
                <template v-if="customer_ids.length">
                    <span
                        class="ml-2 font-mono text-[11px] font-bold uppercase tracking-widest text-black/40 dark:text-white/40"
                    >
                        Customers
                    </span>
                    <div v-for="filter in customer_ids" :key="`cust-${filter}`">
                        <CustomerTag
                            :filter
                            :customers
                            @remove="(id) => remove_customer_filter(id)"
                        />
                    </div>
                </template>
            </div>

            <!-- Pagination (top) -->
            <div class="mb-4 flex justify-between">
                <ManageJob :new="true" :states :customers></ManageJob>
                <div>
                    <Paginator
                        :links="props.jobs.meta.links"
                        @update:model-value="(d) => {
                            console.log(d); page = parseInt(d)
                        }"
                    />
                </div>
            </div>

            <!-- Job cards -->
            <div class="flex flex-col gap-4">
                <div
                    v-for="(job, index) in props.jobs.data"
                    :key="index"
                    class="group relative overflow-hidden rounded-2xl border-2 border-black bg-light-primary shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-transform duration-200 hover:-translate-y-0.5 dark:border-white dark:bg-dark-secondary"
                >
                    <!-- status stripe -->
                    <div
                        class="absolute inset-y-0 left-0 w-2 border-r-2 border-black dark:border-white"
                        :class="stripeClass(job)"
                    ></div>

                    <div
                        class="flex flex-col gap-5 p-5 pl-7 lg:flex-row lg:items-start lg:justify-between"
                    >
                        <!-- Job info -->
                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-3">
                                <span
                                    class="inline-flex items-center rounded-lg border-2 border-black bg-black px-2.5 py-1 font-mono text-xs font-bold tracking-wide text-white dark:border-white dark:bg-white dark:text-black"
                                >
                                    D{{
                                        new Date(job.created_at).getFullYear()
                                    }}-{{ job.number }}
                                </span>

                                <ManageJob
                                    :new="false"
                                    :job
                                    :states
                                    :customers
                                ></ManageJob>

                                <span
                                    class="rounded-md px-2 py-0.5 text-[11px] font-bold uppercase tracking-wide"
                                    :class="
                                        job.prevailing_wage
                                            ? 'bg-accent/20 text-accent'
                                            : 'bg-black/5 text-black/40 dark:bg-white/10 dark:text-white/40'
                                    "
                                >
                                    {{
                                        job.prevailing_wage
                                            ? "Prevailing wage"
                                            : "Non-prevailing wage"
                                    }}
                                </span>
                            </div>

                            <h3
                                class="mt-3 truncate text-base font-extrabold text-black dark:text-white"
                            >
                                {{ job.address }}
                            </h3>
                            <p
                                class="text-sm font-medium text-black/50 dark:text-white/50"
                            >
                                {{ job.city }}, {{ job.state.state }}
                                {{ job.zip }}
                            </p>

                            <div
                                class="mt-3 flex flex-wrap items-center gap-4 text-xs font-bold text-black/50 dark:text-white/50"
                            >
                                <span class="inline-flex items-center gap-2">
                                    <span
                                        class="flex h-6 w-6 items-center justify-center rounded-full border-2 border-black bg-light-quatrenary font-mono text-[10px] font-bold text-black dark:border-white"
                                    >
                                        {{ job.customer.name.charAt(0) }}
                                    </span>
                                    {{ job.customer.name }}
                                </span>
                                <span
                                    >Start
                                    {{
                                        useDateFormat(
                                            job.start_date,
                                            "M/D/YYYY",
                                        )
                                    }}</span
                                >
                            </div>
                        </div>

                        <!-- Proposals -->
                        <div class="w-full shrink-0 lg:w-[440px]">
                            <div class="mb-2 flex items-center justify-between">
                                <span
                                    class="font-mono text-[11px] font-bold uppercase tracking-widest text-black/40 dark:text-white/40"
                                >
                                    Proposals
                                </span>
                                <Link
                                    :href="
                                        route('proposals.store', {
                                            job: job.id,
                                        })
                                    "
                                    method="post"
                                >
                                    <Button
                                        class="h-7 gap-1 rounded-lg border-2 border-black bg-white px-2.5 text-xs font-bold text-black shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-[transform,background-color] duration-200 hover:-translate-y-0.5 hover:bg-light-quatrenary dark:border-white dark:bg-black/20 dark:text-white dark:hover:bg-dark-tertiary"
                                    >
                                        <Plus
                                            class="h-3.5 w-3.5"
                                            :stroke-width="3"
                                        />
                                        New proposal
                                    </Button>
                                </Link>
                            </div>

                            <div
                                v-if="!job.proposals.length"
                                class="rounded-xl border-2 border-dashed border-black/20 p-3 text-center text-xs font-bold text-black/30 dark:border-white/20 dark:text-white/30"
                            >
                                No proposals yet
                            </div>

                            <div v-else class="flex flex-col gap-2">
                                <div
                                    v-for="(proposal, i) in job.proposals"
                                    :key="i"
                                    class="flex items-center justify-between gap-3 rounded-xl border-2 border-black bg-white px-3 py-2 dark:border-white dark:bg-black/20"
                                >
                                    <div class="min-w-0">
                                        <p
                                            class="truncate text-sm font-bold text-black dark:text-white"
                                        >
                                            {{ proposal.name }}
                                        </p>
                                        <p
                                            class="truncate text-[11px] font-medium text-black/40 dark:text-white/40"
                                        >
                                            {{ proposal.type }} ·
                                            {{ proposal.estimator.name }} ·
                                            {{
                                                useDateFormat(
                                                    proposal.created_at,
                                                    "M/D/YYYY",
                                                )
                                            }}
                                        </p>
                                    </div>

                                    <div
                                        class="flex shrink-0 items-center gap-1.5"
                                    >
                                        <span
                                            class="mr-1 font-mono text-sm font-extrabold tabular-nums text-black dark:text-white"
                                        >
                                            {{
                                                formatWithCommas(
                                                    proposalTotal(proposal),
                                                    "currency",
                                                )
                                            }}
                                        </span>

                                        <Link
                                            :href="
                                                route('proposals.edit', {
                                                    proposal: proposal.id,
                                                })
                                            "
                                        >
                                            <Button
                                                class="h-8 w-8 rounded-lg border-2 border-black bg-white p-0 text-black shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-[transform,background-color] duration-200 hover:-translate-y-0.5 hover:bg-light-quatrenary dark:border-white dark:bg-black/20 dark:text-white dark:hover:bg-dark-tertiary"
                                                aria-label="Edit proposal"
                                            >
                                                <ArrowUpRight
                                                    class="h-4 w-4"
                                                    :stroke-width="2.5"
                                                />
                                            </Button>
                                        </Link>

                                        <a
                                            :href="
                                                route('proposals.downloadPDF', {
                                                    proposal: proposal.id,
                                                })
                                            "
                                        >
                                            <Button
                                                class="h-8 w-8 rounded-lg border-2 border-black bg-accent p-0 text-white shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-[transform,background-color] duration-200 hover:-translate-y-0.5 dark:border-white"
                                                aria-label="Download proposal PDF"
                                            >
                                                <Download
                                                    class="h-4 w-4"
                                                    :stroke-width="2.5"
                                                />
                                            </Button>
                                        </a>

                                        <a
                                            target="_blank"
                                            :href="
                                                route('proposals.browserPDF', {
                                                    proposal: proposal.id,
                                                })
                                            "
                                        >
                                            <Button
                                                class="h-8 w-8 rounded-lg border-2 border-black bg-accent p-0 text-white shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-[transform,background-color] duration-200 hover:-translate-y-0.5 dark:border-white"
                                                aria-label="Open proposal PDF"
                                            >
                                                <FileText
                                                    class="h-4 w-4"
                                                    :stroke-width="2.5"
                                                />
                                            </Button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </EstimatingLayout>
</template>
