<script setup>
import { computed, ref, watch } from "vue";
import { Head, router } from "@inertiajs/vue3";
import { useDateFormat } from "@vueuse/core";
import EstimatingLayout from "@/layouts/EstimatingLayout.vue";
import { Button } from "@/components/ui/button";
import Manage from "./modals/ManageCustomer.vue";
import Delete from "./modals/DeleteCustomer.vue";
import SearchBox from "../jobs/partials/SearchBox.vue";
import State from "../jobs/partials/State.vue";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table"
import { ArrowDown, ArrowUp } from "lucide-vue-next";

const props = defineProps({
    customers: Object,
    states: Object,
    filtered_states: Object,
    filters: Object,
});

const search = ref(props.filters.search);
const order = ref(props.filters.order);
const state = ref(props.filters.state);

const states = computed(() => {
    return [{ id: null, state: "Select" }, ...props.states];
});

const newCustomer = {
    id: null,
    name: "",
    state: {
        id: null,
        abbr: "",
        state: "",
    },
};

watch(
    () => order.value,
    () => {
        getCustomers();
    }
);

const getCustomers = () => {
    router.reload({
        data: {
            search: search.value,
            order: order.value,
            state: state.value,
        },
        only: ["customers", "filters"],
        replace: true,
    });
};

const sortIcon = computed(() => {
    return order.value == "desc" ? ArrowUp : ArrowDown;
})

const formatDate = (date) => useDateFormat(date, "M.D.YYYY").value

// Shared eyebrow token, matching the column/section labels used everywhere
// else (Filters group headers, widget titles, etc).
const eyebrowClass = 'font-mono text-[11px] font-bold uppercase tracking-widest text-black/40 dark:text-white/40'
</script>

<template>
    <EstimatingLayout>

        <Head title="Customers" />

        <div class="mx-auto w-full max-w-7xl px-4 py-8">

            <!-- Page header -->
            <div class="mb-6">
                <p class="font-mono text-xs font-bold uppercase tracking-[0.2em] text-black/40 dark:text-white/40">
                    Estimating
                </p>
                <h1 class="text-3xl font-extrabold tracking-tight text-black dark:text-white">
                    Customers
                </h1>
            </div>

            <!-- Toolbar -->
            <div
                class="mb-5 flex flex-col gap-4 rounded-2xl border-2 border-black bg-light-primary p-4 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] dark:border-white dark:bg-dark-primary"
            >
                <!-- Primary row: search + new customer -->
                <div class="flex items-center gap-3">
                    <div class="min-w-0 flex-1">
                        <SearchBox id="search" v-model="search" @update:model-value="getCustomers()"></SearchBox>
                    </div>

                    <Manage :new="true" :customer="newCustomer" :states></Manage>
                </div>

                <div class="h-px w-full bg-black/10 dark:bg-white/10"></div>

                <!-- Refine row: sort + state, each under its own label -->
                <div class="flex flex-wrap items-center gap-4">
                    <div class="flex items-center gap-2">
                        <span :class="eyebrowClass">Sort</span>
                        <Button
                            class="h-9 gap-1.5 rounded-lg border-2 border-black bg-white px-3 text-xs font-bold text-black shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-[transform,background-color] duration-200 hover:-translate-y-0.5 hover:bg-light-quatrenary dark:border-white dark:bg-black/20 dark:text-white dark:hover:bg-dark-tertiary"
                            @click="order = order == 'asc' ? 'desc' : 'asc'"
                        >
                            <component :is="sortIcon" class="h-3.5 w-3.5" :stroke-width="3" />
                            {{ order == 'asc' ? 'A–Z' : 'Z–A' }}
                        </Button>
                    </div>

                    <div class="hidden h-8 w-px bg-black/10 dark:bg-white/10 sm:block"></div>

                    <div class="flex items-center gap-2">
                        <span :class="eyebrowClass">State</span>
                        <State id="state" v-model="state" :states @update:model-value="getCustomers()"></State>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div
                class="overflow-hidden rounded-2xl border-2 border-black bg-light-primary shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] dark:border-white dark:bg-dark-secondary"
            >
                <Table class="w-full">
                    <TableHeader>
                        <TableRow class="border-b-2 border-black/10 hover:bg-transparent dark:border-white/10">
                            <TableHead :class="[eyebrowClass, 'py-3']">Customer</TableHead>
                            <TableHead :class="eyebrowClass">State</TableHead>
                            <TableHead :class="eyebrowClass">Created</TableHead>
                            <TableHead :class="eyebrowClass">Updated</TableHead>
                            <TableHead :class="[eyebrowClass, 'text-right']" colspan="2">Actions</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-if="!props.customers.length" class="hover:bg-transparent">
                            <TableCell colspan="6" class="p-0">
                                <p class="p-6 text-center text-xs font-bold text-black/30 dark:text-white/30">
                                    No customers found
                                </p>
                            </TableCell>
                        </TableRow>

                        <TableRow
                            v-for="(customer, index) in props.customers" :key="index"
                            class="border-b-2 border-black/10 transition-colors last:border-b-0 hover:bg-light-quatrenary dark:border-white/10 dark:hover:bg-dark-tertiary"
                        >
                            <TableCell class="py-2.5 font-bold text-black dark:text-white">{{ customer.name }}</TableCell>
                            <TableCell class="text-black/70 dark:text-white/70">{{ customer.state.state }}</TableCell>
                            <TableCell class="font-mono text-xs text-black/50 dark:text-white/50">{{ formatDate(customer.created_at) }}</TableCell>
                            <TableCell class="font-mono text-xs text-black/50 dark:text-white/50">{{ formatDate(customer.updated_at) }}</TableCell>
                            <TableCell class="text-right">
                                <Manage :new="false" :customer :states />
                            </TableCell>
                            <TableCell class="text-right">
                                <Delete :customer="customer" />
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>
        </div>

    </EstimatingLayout>
</template>