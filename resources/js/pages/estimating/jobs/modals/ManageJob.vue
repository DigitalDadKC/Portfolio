<script setup lang="ts">
import { ref, watch } from "vue";
import { useForm } from "@inertiajs/vue3";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Button } from "@/components/ui/button";
import { Textarea } from "@/components/ui/textarea";
import Customer from "../partials/Customer.vue";
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from "@/components/ui/dialog";
import StartDate from "../partials/StartDate.vue";
import State from "../partials/State.vue";
import Switch from "@/components/ui/switch/Switch.vue";
import { Pencil, Plus, Trash2 } from "lucide-vue-next";

const props = defineProps({
    new: Boolean,
    job: Object,
    states: Object,
    customers: Object,
});

const isDialogOpen = ref(false);

const form = useForm({
    id: props.job?.id,
    number: props.job?.number,
    address: props.job?.address,
    city: props.job?.city,
    state_id: props.job?.state?.id,
    zip: props.job?.zip,
    notes: props.job?.notes,
    customer_id: props.job?.customer?.id,
    start_date: props.job?.start_date,
    prevailing_wage: props.job?.prevailing_wage ?? false,
    created_at: props.job?.created_at,
    updated_at: props.job?.updated_at,
});

const syncForm = () => {
    if (props.new) {
        form.reset();
        form.clearErrors();

        form.prevailing_wage = false;

        return;
    }

    form.defaults({
        id: props.job?.id,
        number: props.job?.number,
        address: props.job?.address,
        city: props.job?.city,
        state_id: props.job?.state?.id,
        zip: props.job?.zip,
        notes: props.job?.notes,
        customer_id: props.job?.customer?.id,
        start_date: props.job?.start_date,
        prevailing_wage: props.job?.prevailing_wage ?? false,
        created_at: props.job?.created_at,
        updated_at: props.job?.updated_at,
    });

    form.reset();
    form.clearErrors();
};

watch(
    () => props.job,
    () => {
        if (!isDialogOpen.value) {
            syncForm();
        }
    },
    { immediate: true },
);

const openDialog = () => {
    syncForm();
    isDialogOpen.value = true;
};

const closeDialog = () => {
    isDialogOpen.value = false;
    form.reset();
    form.clearErrors();
};

const submit = () => {
    if (props.new) {
        form.post(route("jobs.store"), {
            preserveScroll: true,
            onSuccess: () => {
                form.reset();
                form.clearErrors();
                isDialogOpen.value = false;
            },
        });

        return;
    }

    form.patch(route("jobs.update", props.job.id), {
        preserveScroll: true,
        onSuccess: () => {
            form.clearErrors();
            isDialogOpen.value = false;
        },
    });
};

const destroy = () => {
    if (props.new || !props.job?.id) {
        return;
    }

    form.delete(route("jobs.destroy", props.job.id), {
        preserveScroll: true,
        onSuccess: () => {
            isDialogOpen.value = false;
        },
    });
};
</script>

<template>
    <Dialog v-model:open="isDialogOpen">
        <DialogTrigger as-child>
            <Button
                v-if="props.new"
                @click="openDialog"
                class="h-10 gap-2 rounded-xl border-2 border-black bg-light-quatrenary px-4 font-bold text-black shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] transition-[transform,background-color] duration-200 hover:-translate-y-0.5 hover:bg-light-tertiary dark:border-white dark:text-black"
            >
                <Plus class="h-4 w-4" :stroke-width="3" />
                New job
            </Button>

            <Button
                v-else
                @click="openDialog"
                class="h-8 w-8 rounded-lg border-2 border-black bg-white p-0 text-black shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-[transform,background-color] duration-200 hover:-translate-y-0.5 hover:bg-light-quatrenary dark:border-white dark:bg-black/20 dark:text-white dark:hover:bg-dark-tertiary"
                aria-label="Edit job"
            >
                <Pencil class="h-3.5 w-3.5" :stroke-width="2.5" />
            </Button>
        </DialogTrigger>

        <DialogContent
            class="fixed left-1/2 top-1/2 grid w-[calc(100%-2rem)] max-w-6xl -translate-x-1/2 -translate-y-1/2 grid-rows-[auto_minmax(0,1fr)_auto] overflow-hidden rounded-2xl border-2 border-black bg-light-primary p-0 text-black shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] dark:border-white dark:bg-dark-primary dark:text-white sm:w-[calc(100%-3rem)]"
        >
            <!-- Header -->
            <DialogHeader
                class="border-b-2 border-black/10 px-6 py-5 dark:border-white/10"
            >
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p
                            class="mb-1 font-mono text-[10px] font-bold uppercase tracking-[0.2em] text-black/40 dark:text-white/40"
                        >
                            Estimating · Jobs
                        </p>

                        <DialogTitle
                            class="text-2xl font-extrabold tracking-tight text-black dark:text-white"
                        >
                            {{ props.new ? "New job" : "Edit job" }}
                        </DialogTitle>

                        <DialogDescription
                            v-if="!props.new"
                            class="mt-1 font-mono text-xs font-bold text-black/40 dark:text-white/40"
                        >
                            D{{ new Date(props.job.created_at).getFullYear() }}-{{
                                props.job.number
                            }}
                        </DialogDescription>

                        <DialogDescription
                            v-else
                            class="mt-1 text-sm font-medium text-black/50 dark:text-white/50"
                        >
                            Create a new estimating job.
                        </DialogDescription>
                    </div>
                </div>
            </DialogHeader>

            <!-- Form -->
            <div
                class="min-h-0 overflow-y-auto px-6 py-6"
            >
                <div class="space-y-7">
                    <!-- Job details -->
                    <section>
                        <div class="mb-4">
                            <h3
                                class="text-sm font-extrabold uppercase tracking-wide text-black dark:text-white"
                            >
                                Job details
                            </h3>
                            <p
                                class="mt-0.5 text-xs font-medium text-black/40 dark:text-white/40"
                            >
                                Basic information for this job.
                            </p>
                        </div>

                        <!-- Customer gets the most room -->
                        <div
                            class="grid gap-4 sm:grid-cols-[1fr_2fr_1.15fr]"
                        >
                            <!-- Job number -->
                            <div class="min-w-0 space-y-2">
                                <Label
                                    for="number"
                                    class="text-xs font-bold uppercase tracking-wide text-black/60 dark:text-white/60"
                                >
                                    Job number
                                </Label>

                                <Input
                                    id="number"
                                    v-model.number="form.number"
                                    type="number"
                                    class="h-10 rounded-lg border-2 border-black bg-white font-mono font-bold text-black shadow-none transition-colors placeholder:text-black/25 focus-visible:ring-0 focus-visible:ring-offset-0 hover:bg-light-quatrenary dark:border-white dark:bg-black/20 dark:text-white dark:hover:bg-dark-tertiary"
                                    :class="{
                                        'border-red-500 dark:border-red-500':
                                            form.errors.number,
                                    }"
                                />

                                <p
                                    v-if="form.errors.number"
                                    class="text-xs font-bold text-red-500"
                                >
                                    {{ form.errors.number }}
                                </p>
                            </div>

                            <!-- Customer -->
                            <div class="min-w-0 space-y-2">
                                <Label
                                    for="customer"
                                    class="text-xs font-bold uppercase tracking-wide text-black/60 dark:text-white/60"
                                >
                                    Customer
                                </Label>

                                <Customer
                                    :customers
                                    v-model="form.customer_id"
                                />

                                <p
                                    v-if="form.errors.customer_id"
                                    class="text-xs font-bold text-red-500"
                                >
                                    {{ form.errors.customer_id }}
                                </p>
                            </div>

                            <!-- Start date -->
                            <div class="min-w-0 space-y-2">
                                <Label
                                    for="start_date"
                                    class="text-xs font-bold uppercase tracking-wide text-black/60 dark:text-white/60"
                                >
                                    Start date
                                </Label>

                                <StartDate
                                    v-model="form.start_date"
                                    :job
                                />

                                <p
                                    v-if="form.errors.start_date"
                                    class="text-xs font-bold text-red-500"
                                >
                                    {{ form.errors.start_date }}
                                </p>
                            </div>
                        </div>

                        <!-- Prevailing wage -->
                        <div
                            class="mt-4 flex items-center justify-between gap-4 rounded-xl border-2 border-black bg-white px-4 py-3 dark:border-white dark:bg-black/20"
                        >
                            <div>
                                <Label
                                    for="prevailing_wage"
                                    class="cursor-pointer text-sm font-extrabold text-black dark:text-white"
                                >
                                    Prevailing wage
                                </Label>

                                <p
                                    class="mt-0.5 text-xs font-medium text-black/40 dark:text-white/40"
                                >
                                    Mark this job as subject to prevailing wage
                                    requirements.
                                </p>
                            </div>

                            <Switch
                                id="prevailing_wage"
                                v-model="form.prevailing_wage"
                            />
                        </div>
                    </section>

                    <!-- Divider -->
                    <div
                        class="h-px bg-black/10 dark:bg-white/10"
                    ></div>

                    <!-- Location -->
                    <section>
                        <div class="mb-4">
                            <h3
                                class="text-sm font-extrabold uppercase tracking-wide text-black dark:text-white"
                            >
                                Job location
                            </h3>
                            <p
                                class="mt-0.5 text-xs font-medium text-black/40 dark:text-white/40"
                            >
                                Where the work will take place.
                            </p>
                        </div>

                        <div class="space-y-4">
                            <!-- Address -->
                            <div class="space-y-2">
                                <Label
                                    for="address"
                                    class="text-xs font-bold uppercase tracking-wide text-black/60 dark:text-white/60"
                                >
                                    Address
                                </Label>

                                <Input
                                    id="address"
                                    v-model="form.address"
                                    autocomplete="street-address"
                                    class="h-10 rounded-lg border-2 border-black bg-white font-medium text-black shadow-none transition-colors placeholder:text-black/25 focus-visible:ring-0 focus-visible:ring-offset-0 hover:bg-light-quatrenary dark:border-white dark:bg-black/20 dark:text-white dark:hover:bg-dark-tertiary"
                                    :class="{
                                        'border-red-500 dark:border-red-500':
                                            form.errors.address,
                                    }"
                                />

                                <p
                                    v-if="form.errors.address"
                                    class="text-xs font-bold text-red-500"
                                >
                                    {{ form.errors.address }}
                                </p>
                            </div>

                            <!-- City / State / Zip -->
                            <div class="grid gap-4 sm:grid-cols-[2fr_1fr_1fr]">
                                <div class="space-y-2">
                                    <Label
                                        for="city"
                                        class="text-xs font-bold uppercase tracking-wide text-black/60 dark:text-white/60"
                                    >
                                        City
                                    </Label>

                                    <Input
                                        id="city"
                                        v-model="form.city"
                                        autocomplete="address-level2"
                                        class="h-10 rounded-lg border-2 border-black bg-white font-medium text-black shadow-none transition-colors placeholder:text-black/25 focus-visible:ring-0 focus-visible:ring-offset-0 hover:bg-light-quatrenary dark:border-white dark:bg-black/20 dark:text-white dark:hover:bg-dark-tertiary"
                                        :class="{
                                            'border-red-500 dark:border-red-500':
                                                form.errors.city,
                                        }"
                                    />

                                    <p
                                        v-if="form.errors.city"
                                        class="text-xs font-bold text-red-500"
                                    >
                                        {{ form.errors.city }}
                                    </p>
                                </div>

                                <div class="space-y-2">
                                    <Label
                                        for="state"
                                        class="text-xs font-bold uppercase tracking-wide text-black/60 dark:text-white/60"
                                    >
                                        State
                                    </Label>

                                    <State
                                        v-model="form.state_id"
                                        :states
                                    />

                                    <p
                                        v-if="form.errors.state_id"
                                        class="text-xs font-bold text-red-500"
                                    >
                                        {{ form.errors.state_id }}
                                    </p>
                                </div>

                                <div class="space-y-2">
                                    <Label
                                        for="zip"
                                        class="text-xs font-bold uppercase tracking-wide text-black/60 dark:text-white/60"
                                    >
                                        ZIP
                                    </Label>

                                    <Input
                                        id="zip"
                                        v-model="form.zip"
                                        autocomplete="postal-code"
                                        class="h-10 rounded-lg border-2 border-black bg-white font-mono font-bold text-black shadow-none transition-colors placeholder:text-black/25 focus-visible:ring-0 focus-visible:ring-offset-0 hover:bg-light-quatrenary dark:border-white dark:bg-black/20 dark:text-white dark:hover:bg-dark-tertiary"
                                        :class="{
                                            'border-red-500 dark:border-red-500':
                                                form.errors.zip,
                                        }"
                                    />

                                    <p
                                        v-if="form.errors.zip"
                                        class="text-xs font-bold text-red-500"
                                    >
                                        {{ form.errors.zip }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Divider -->
                    <div
                        class="h-px bg-black/10 dark:bg-white/10"
                    ></div>

                    <!-- Notes -->
                    <section>
                        <div class="mb-4">
                            <h3
                                class="text-sm font-extrabold uppercase tracking-wide text-black dark:text-white"
                            >
                                Notes
                            </h3>
                            <p
                                class="mt-0.5 text-xs font-medium text-black/40 dark:text-white/40"
                            >
                                Add any additional information for the job.
                            </p>
                        </div>

                        <Textarea
                            id="notes"
                            v-model="form.notes"
                            rows="4"
                            class="resize-none rounded-xl border-2 border-black bg-white font-medium text-black shadow-none placeholder:text-black/25 focus-visible:ring-0 focus-visible:ring-offset-0 hover:bg-light-quatrenary dark:border-white dark:bg-black/20 dark:text-white dark:hover:bg-dark-tertiary"
                            :class="{
                                'border-red-500 dark:border-red-500':
                                    form.errors.notes,
                            }"
                        />

                        <p
                            v-if="form.errors.notes"
                            class="mt-2 text-xs font-bold text-red-500"
                        >
                            {{ form.errors.notes }}
                        </p>
                    </section>
                </div>
            </div>

            <!-- Footer -->
            <DialogFooter
                class="flex flex-col-reverse gap-3 border-t-2 border-black/10 bg-black/[0.02] px-6 py-4 dark:border-white/10 dark:bg-white/[0.02] sm:flex-row sm:items-center sm:justify-between"
            >
                <Button
                    v-if="!props.new"
                    type="button"
                    variant="outline"
                    :disabled="form.processing"
                    @click="destroy"
                    class="h-9 gap-2 rounded-lg border-2 border-red-500 bg-transparent px-3 font-bold text-red-500 shadow-[2px_2px_0px_0px_rgba(239,68,68,1)] transition-[transform,background-color] duration-200 hover:-translate-y-0.5 hover:bg-red-50 dark:hover:bg-red-950/30"
                >
                    <Trash2 class="h-3.5 w-3.5" :stroke-width="2.5" />
                    Delete job
                </Button>

                <div
                    v-else
                    class="hidden sm:block"
                ></div>

                <div class="flex items-center justify-end gap-2">
                    <Button
                        type="button"
                        variant="outline"
                        :disabled="form.processing"
                        @click="closeDialog"
                        class="h-9 rounded-lg border-2 border-black bg-white px-4 font-bold text-black shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-[transform,background-color] duration-200 hover:-translate-y-0.5 hover:bg-light-quatrenary dark:border-white dark:bg-black/20 dark:text-white dark:hover:bg-dark-tertiary"
                    >
                        Cancel
                    </Button>

                    <Button
                        type="button"
                        :disabled="form.processing"
                        @click="submit"
                        class="h-9 min-w-20 rounded-lg border-2 border-black bg-accent px-4 font-bold text-white shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-[transform,background-color] duration-200 hover:-translate-y-0.5 hover:bg-accent/90 dark:border-white"
                    >
                        {{ form.processing ? "Saving…" : "Save" }}
                    </Button>
                </div>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
