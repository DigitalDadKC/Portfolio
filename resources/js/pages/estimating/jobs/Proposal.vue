<script setup lang="ts">
import { computed } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import ProposalLayout from '@/layouts/ProposalLayout.vue';
import { Label } from 'reka-ui';
import { Button } from '@/components/ui/button';
import { Textarea } from '@/components/ui/textarea';
import Scope from './Scope.vue';
import FormattedInput from '@/components/FormattedInput.vue';
import Type from './partials/Type.vue';
import { Plus } from 'lucide-vue-next';
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
    router.put(route('proposals.update', props.proposal?.id), {
        name: props.proposal?.name,
        type: props.proposal?.type,
        exclusions: props.proposal?.exclusions
    }, {
        preserveScroll: true,
    })
}

const addScope = () => {
    router.post(route('scopes.create', props.proposal?.id), {
    }, {
        preserveScroll: true,
    })
}

const job_year = computed(() => {
    const date = props.proposal?.job?.start_date;
    return date ? new Date(date).getFullYear() : new Date().getFullYear();
});

const total = computed(() => {
    return props.proposal?.scopes.map(scope => scope.lines.map(line => ((line.price * line.quantity*100)/100)).reduce((a, b) => a + b, 0)).reduce((a, b) => a + b, 0)
})

const eyebrowClass = 'font-mono text-[11px] font-bold uppercase tracking-widest text-black/40 dark:text-white/40'

const fieldClass = 'rounded-lg border-2 border-black bg-white font-bold text-black shadow-none dark:border-white dark:bg-black/20 dark:text-white'

</script>

<template>
    <Head title="Proposal" />

    <ProposalLayout :proposal-id="props.proposal.id">
        <template #header>
            <h2 class="text-center text-xl font-extrabold leading-tight text-black dark:text-white">Proposal</h2>
        </template>

        <div class="mx-auto w-full max-w-6xl px-4 py-8">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <div class="flex flex-wrap items-center gap-3">
                        <span
                            class="inline-flex items-center rounded-lg border-2 border-black bg-black px-2.5 py-1 font-mono text-xs font-bold tracking-wide text-white dark:border-white dark:bg-white dark:text-black"
                        >
                            D{{ job_year }}-{{ props.proposal.job.number }}
                        </span>
                    </div>

                    <h1 class="mt-3 text-3xl font-extrabold tracking-tight text-black dark:text-white">
                        {{ props.proposal.name || 'New proposal' }}
                    </h1>
                    <p class="text-sm font-medium text-black/50 dark:text-white/50">
                        {{ props.proposal.job.address }}, {{ props.proposal.job.city }}, {{ props.proposal.job.state.state }} {{ props.proposal.job.zip }}
                    </p>
                </div>
            </div>

            <div class="p-2 flex flex-col bg-light-primary rounded-2xl border-2 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] dark:bg-dark-primary">

                <div class="p-4 flex flex-col gap-3 bg-light-primary border-black border-b-2 dark:border-white dark:bg-dark-primary sm:flex-row sm:items-start sm:justify-between">
                    <div class="flex min-w-0 flex-1 flex-col gap-4">
                        <div class="min-w-0">
                            <Label for="name" :class="eyebrowClass">Proposal name</Label>
                            <FormattedInput
                                id="name"
                                width="full"
                                v-model="props.proposal.name"
                                @blur="updateProposal()"
                                :class="fieldClass"
                            />
                        </div>

                        <div class="min-w-0">
                            <Type
                                :types
                                v-model="props.proposal.type"
                                @update:modelValue="updateProposal()"
                            />
                        </div>
                    </div>

                    <div class="flex shrink-0 flex-col items-start gap-3 lg:w-64 lg:items-end lg:border-l-2 lg:border-black/10 lg:pl-6 dark:lg:border-white/10">
                        <p :class="[eyebrowClass, 'lg:self-end']">Prepared by</p>

                        <img
                            v-if="props.company?.logo"
                            :src="props.company.logo"
                            class="h-14 w-auto rounded-lg border-2 border-black object-contain shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] dark:border-white"
                            aria-label="company logo"
                        />

                        <div class="flex flex-col gap-0.5 text-left lg:items-end lg:text-right">
                            <p class="text-sm font-extrabold text-black dark:text-white">
                                {{ props.company?.name }}
                            </p>
                            <p class="text-xs font-medium text-black/50 dark:text-white/50">
                                {{ props.company?.address }}
                            </p>
                            <p class="text-xs font-medium text-black/50 dark:text-white/50">
                                {{ props.company?.city }} {{ props.company?.state?.abbr }}<span v-if="props.company">,</span> {{ props.company?.zip }}
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="flex flex-wrap items-center justify-between gap-3 px-2 py-3">
                    <span :class="eyebrowClass">Scopes</span>

                    <Button
                        type="button"
                        class="h-9 gap-1.5 rounded-lg border-2 border-black bg-white px-3 text-xs font-bold text-black shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-[transform,background-color] duration-200 hover:-translate-y-0.5 hover:bg-light-quatrenary dark:border-white dark:bg-black/20 dark:text-white dark:hover:bg-dark-tertiary"
                        @click="addScope()"
                    >
                        <Plus class="h-3.5 w-3.5" :stroke-width="3" />
                        Add scope
                    </Button>
                </div>

                <div class="flex min-w-0 flex-col gap-4">
                    <div
                        v-if="!props.proposal.scopes.length"
                        class="rounded-xl border-2 border-dashed border-black/20 px-4 py-8 text-center text-sm font-medium text-black/50 dark:border-white/20 dark:text-white/50"
                    >
                        No scopes yet — add one to start building the proposal.
                    </div>

                    <div
                        v-for="scope in props.proposal.scopes"
                        :key="scope.id"
                        class="min-w-0"
                    >
                        <Scope :scope="scope" :index="props.proposal.scopes.indexOf(scope)" :unit_of_measurements="unit_of_measurements" />
                    </div>

                    <!-- Total -->
                    <div class="flex justify-end border-t-2 border-black/10 pt-4 dark:border-white/10">
                        <div class="w-full rounded-xl border-2 border-black bg-white px-4 py-3 text-right dark:border-white dark:bg-black/20 sm:w-auto sm:min-w-56">
                            <p :class="eyebrowClass">Proposal total</p>
                            <p class="break-words font-mono text-2xl font-extrabold tabular-nums text-black dark:text-white">
                                {{ formatWithCommas(total, 'currency') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 rounded-2xl border-2 border-black bg-light-primary p-4 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] dark:border-white dark:bg-dark-primary sm:p-5">
                <Label
                    for="exclusions"
                    :class="[eyebrowClass, 'mb-3 block']"
                >
                    Exclusions
                </Label>

                <Textarea
                    id="exclusions"
                    v-model="props.proposal.exclusions"
                    @blur="updateProposal()"
                    placeholder="List any work, materials, or services excluded from this proposal..."
                    class="min-h-32 w-full resize-y rounded-lg border-2 border-black bg-white font-medium text-black focus-visible:ring-0 dark:border-white dark:bg-black/20 dark:text-white"
                />
            </div>
        </div>

    </ProposalLayout>
</template>