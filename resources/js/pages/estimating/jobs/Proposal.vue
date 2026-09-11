<script setup>
import { computed } from 'vue';
import { Link, Head, router } from '@inertiajs/vue3';
import GuestLayout from '@/layouts/GuestLayout.vue';
import { Label } from 'reka-ui';
import { Button } from '@/components/ui/button';
import { Textarea } from '@/components/ui/textarea';
import Scope from './Scope.vue';
import FormattedInput from '@/components/FormattedInput.vue';
import Type from './partials/Type.vue';
import { ArrowLeft, Plus, Trash2 } from 'lucide-vue-next';
import { useDateFormat } from '@vueuse/core';
import { useFormatCurrency } from '@/composables/useFormatCurrency';

const props = defineProps({
    new: Boolean,
    proposal: Object,
    company: Object,
    states: Object,
    types: Object,
    unit_of_measurements: Object,
    errors: Object
})

const { formatWithCommas } = useFormatCurrency()

const updateProposal = () => {
    router.put(route('proposals.update', props.proposal.id), {
        name: props.proposal.name,
        type: props.proposal.type,
        exclusions: props.proposal.exclusions
    }, {
        preserveScroll: true,
    })
}

const destroyProposal = () => {
    router.delete(route('proposals.destroy', props.proposal.id))
}

const addScope = () => {
    router.post(route('scopes.create', props.proposal.id), {
    }, {
        preserveScroll: true,
    })
}

const state_date = computed(() => {
    return useDateFormat(props.proposal.job.start_date, 'MMM DD, YYYY').value
})

const job_year = computed(() => {
    return new Date(props.proposal.job.start_date).getFullYear()
})

const total = computed(() => {
    return props.proposal.scopes.map(scope => scope.lines.map(line => ((line.price * line.quantity*100)/100)).reduce((a, b) => a + b, 0)).reduce((a, b) => a + b, 0)
})

// Shared eyebrow / label token used above every field and section, matching
// the "Estimating" / "Proposals" eyebrows on the Jobs index page.
const eyebrowClass = 'font-mono text-[11px] font-bold uppercase tracking-widest text-black/40 dark:text-white/40'

// Shared control classes so read-only fields and the exclusions textarea sit
// on the same bordered/shadowed surface as everything else in the app.
const fieldClass = 'rounded-lg border-2 border-black bg-white font-bold text-black shadow-none dark:border-white dark:bg-black/20 dark:text-white'
const readonlyFieldClass = `${fieldClass} text-black/60 dark:text-white/60`

</script>

<template>
    <Head title="Proposal" />

    <GuestLayout title="Construction Estimating Software">
        <template #header>
            <h2 class="text-center text-xl font-extrabold leading-tight text-black dark:text-white">Proposal</h2>
        </template>

        <div class="mx-auto w-full max-w-6xl px-4 py-8">

            <!-- Breadcrumb back to the parent job -->
            <Link :href="route('estimating.jobs.index')"
                class="mb-4 inline-flex items-center gap-1.5 font-mono text-xs font-bold uppercase tracking-widest text-black/40 transition-colors hover:text-black dark:text-white/40 dark:hover:text-white"
            >
                <ArrowLeft class="h-3.5 w-3.5" :stroke-width="3" />
                Back to jobs
            </Link>

            <!-- Page header -->
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <div class="flex flex-wrap items-center gap-3">
                        <span
                            class="inline-flex items-center rounded-lg border-2 border-black bg-black px-2.5 py-1 font-mono text-xs font-bold tracking-wide text-white dark:border-white dark:bg-white dark:text-black"
                        >
                            D{{ job_year }}-{{ props.proposal.job.number }}
                        </span>

                        <Type :types v-model="props.proposal.type" @update:modelValue="updateProposal()"></Type>
                    </div>

                    <h1 class="mt-3 text-3xl font-extrabold tracking-tight text-black dark:text-white">
                        {{ props.proposal.name || 'New proposal' }}
                    </h1>
                    <p class="text-sm font-medium text-black/50 dark:text-white/50">
                        {{ props.proposal.job.address }}, {{ props.proposal.job.city }}, {{ props.proposal.job.state.state }} {{ props.proposal.job.zip }}
                    </p>
                </div>

                <Button
                    class="h-9 shrink-0 gap-1.5 rounded-lg border-2 border-black bg-white px-3 text-xs font-bold text-black shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-[transform,background-color] duration-200 hover:-translate-y-0.5 hover:bg-red-50 dark:border-white dark:bg-black/20 dark:text-white dark:hover:bg-red-950/40"
                    @click="destroyProposal()"
                >
                    <Trash2 class="h-3.5 w-3.5" :stroke-width="2.5" />
                    Delete proposal
                </Button>
            </div>

            <!-- Job + company card -->
            <div
                class="mb-5 flex flex-col gap-6 rounded-2xl border-2 border-black bg-light-primary p-5 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] dark:border-white dark:bg-dark-primary lg:flex-row lg:justify-between"
            >
                <div class="min-w-0 flex-1">
                    <p :class="[eyebrowClass, 'mb-3']">Job details</p>
                    <div class="grid grid-cols-4 gap-4">
                        <div class="col-span-4 md:col-span-1">
                            <Label for="number" :class="eyebrowClass">Number</Label>
                            <FormattedInput id="number" type="number" width="full" :disabled="true"
                                v-model="props.proposal.job.number" :class="readonlyFieldClass" />
                        </div>
                        <div class="col-span-4 md:col-span-3">
                            <Label for="address" :class="eyebrowClass">Address</Label>
                            <FormattedInput id="address" width="full" :disabled="true"
                                v-model="props.proposal.job.address" :class="readonlyFieldClass" />
                        </div>
                        <div class="col-span-4 md:col-span-2">
                            <Label for="city" :class="eyebrowClass">City</Label>
                            <FormattedInput id="city" width="full" :disabled="true"
                                v-model="props.proposal.job.city" :class="readonlyFieldClass" />
                        </div>
                        <div class="col-span-4 md:col-span-2">
                            <Label for="state" :class="eyebrowClass">State</Label>
                            <FormattedInput id="state" width="full" :disabled="true"
                                v-model="props.proposal.job.state.state" :class="readonlyFieldClass" />
                        </div>
                        <div class="col-span-4 md:col-span-2">
                            <Label for="zip" :class="eyebrowClass">Zip</Label>
                            <FormattedInput id="zip" width="full" :disabled="true"
                                v-model="props.proposal.job.zip" :class="readonlyFieldClass" />
                        </div>
                        <div class="col-span-4 md:col-span-2">
                            <Label for="start_date" :class="eyebrowClass">Start date</Label>
                            <FormattedInput id="start_date" width="full" :disabled="true"
                                v-model="state_date" :class="readonlyFieldClass" />
                        </div>
                        <div class="col-span-4">
                            <Label for="notes" :class="eyebrowClass">Job notes</Label>
                            <Textarea id="notes" v-model="props.proposal.job.notes"
                                class="rounded-lg border-2 border-black bg-white font-medium text-black focus-visible:ring-0 dark:border-white dark:bg-black/20 dark:text-white"
                                rows="8" :disabled="true" />
                        </div>
                    </div>
                </div>

                <div class="flex shrink-0 flex-col items-start gap-3 lg:w-64 lg:items-end lg:border-l-2 lg:border-black/10 lg:pl-6 dark:lg:border-white/10">
                    <p :class="[eyebrowClass, 'lg:self-end']">Prepared by</p>
                    <img v-if="props.company?.logo" :src="props.company.logo"
                        class="h-14 w-auto rounded-lg border-2 border-black object-contain shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] dark:border-white" aria-label="company logo" />
                    <div class="flex flex-col gap-0.5 text-left lg:items-end lg:text-right">
                        <p class="text-sm font-extrabold text-black dark:text-white">{{ props.company?.name }}</p>
                        <p class="text-xs font-medium text-black/50 dark:text-white/50">{{ props.company?.address }}</p>
                        <p class="text-xs font-medium text-black/50 dark:text-white/50">
                            {{ props.company?.city }} {{ props.company?.state?.abbr }}<span v-if="props.company">,</span> {{ props.company?.zip }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Proposal name -->
            <div
                class="mb-5 flex flex-col gap-4 rounded-2xl border-2 border-black bg-light-primary p-5 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] dark:border-white dark:bg-dark-primary sm:flex-row sm:items-end sm:justify-between"
            >
                <div class="min-w-0 flex-1">
                    <Label for="name" :class="eyebrowClass">Proposal name</Label>
                    <FormattedInput id="name" width="full" v-model="props.proposal.name" @blur="updateProposal()"
                        :class="fieldClass" />
                </div>
            </div>

            <!-- Scopes -->
            <div class="mb-3 flex items-center justify-between">
                <span :class="eyebrowClass">Scopes</span>
                <Button
                    class="h-8 gap-1.5 rounded-lg border-2 border-black bg-white px-3 text-xs font-bold text-black shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-[transform,background-color] duration-200 hover:-translate-y-0.5 hover:bg-light-quatrenary dark:border-white dark:bg-black/20 dark:text-white dark:hover:bg-dark-tertiary"
                    @click.prevent="addScope()"
                >
                    <Plus class="h-3.5 w-3.5" :stroke-width="3" />
                    Add scope
                </Button>
            </div>

            <form @submit.prevent="submit()" class="flex flex-col gap-4">
                <div
                    v-if="!props.proposal.scopes.length"
                    class="rounded-xl border-2 border-dashed border-black/20 p-6 text-center text-xs font-bold text-black/30 dark:border-white/20 dark:text-white/30"
                >
                    No scopes yet — add one to start building the proposal
                </div>

                <div v-for="(scope, index) in props.proposal.scopes" :key="index"
                    class="rounded-2xl border-2 border-black bg-light-tertiary p-5 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] dark:border-white dark:bg-dark-quatrenary"
                >
                    <Scope :index :scope :unit_of_measurements />
                </div>

                <!-- Total -->
                <div class="flex justify-end">
                    <div class="rounded-xl border-2 border-black bg-white px-4 py-3 text-right dark:border-white dark:bg-black/20">
                        <p :class="eyebrowClass">Total</p>
                        <p class="font-mono text-2xl font-extrabold tabular-nums text-black dark:text-white">
                            {{ formatWithCommas(total, 'currency') }}
                        </p>
                    </div>
                </div>
            </form>

            <!-- Exclusions -->
            <div class="mb-6 mt-5 rounded-2xl border-2 border-black bg-light-primary p-5 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] dark:border-white dark:bg-dark-primary">
                <Label for="exclusions" :class="[eyebrowClass, 'mb-2 block']">Exclusions</Label>
                <Textarea id="exclusions"
                    class="rounded-lg border-2 border-black bg-white font-medium text-black focus-visible:ring-0 dark:border-white dark:bg-black/20 dark:text-white"
                    v-model="props.proposal.exclusions" @blur="updateProposal()" />
            </div>

            <!-- Footer actions -->
            <div class="flex items-center justify-end">
                <Link :href="route('estimating.jobs.index')" as="button" prefetch>
                    <Button
                        class="h-9 gap-1.5 rounded-lg border-2 border-black bg-white px-3 text-xs font-bold text-black shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-[transform,background-color] duration-200 hover:-translate-y-0.5 hover:bg-light-quatrenary dark:border-white dark:bg-black/20 dark:text-white dark:hover:bg-dark-tertiary"
                    >
                        Back
                    </Button>
                </Link>
            </div>
        </div>

    </GuestLayout>
</template>