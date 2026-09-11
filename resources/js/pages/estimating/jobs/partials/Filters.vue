<script setup lang="ts">
import { computed } from 'vue'
import { Button } from '@/components/ui/button'
import {
    DropdownMenu,
    DropdownMenuTrigger,
    DropdownMenuContent,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuCheckboxItem,
} from '@/components/ui/dropdown-menu'
import { SlidersHorizontal, Check } from 'lucide-vue-next'

const props = defineProps({
    states: {
        type: Object,
        default: () => [],
    },
    customers: {
        type: Object,
        default: () => [],
    },
})

const selectedStates = defineModel<number[]>('selectedStates', {
    default: () => [],
})

const selectedCustomers = defineModel<number[]>('selectedCustomers', {
    default: () => [],
})

const filterCount = computed(
    () => selectedStates.value.length + selectedCustomers.value.length
)

const toggleState = (id: number) => {
    console.log('idiot')
    selectedStates.value = selectedStates.value.includes(id)
        ? selectedStates.value.filter(stateId => stateId !== id)
        : [...selectedStates.value, id]
}

const toggleCustomer = (id: number) => {
    selectedCustomers.value = selectedCustomers.value.includes(id)
        ? selectedCustomers.value.filter(customerId => customerId !== id)
        : [...selectedCustomers.value, id]
}

const clearFilters = () => {
    selectedStates.value = []
    selectedCustomers.value = []
}
</script>

<template>
    <DropdownMenu>
        <DropdownMenuTrigger as-child>
            <Button
                class="h-9 gap-2 rounded-lg border-2 border-black bg-light-quatrenary px-3 font-bold text-black shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-[transform,background-color] duration-200 hover:-translate-y-0.5 hover:bg-light-tertiary dark:border-white dark:bg-dark-tertiary dark:text-white dark:hover:bg-dark-secondary"
            >
                <SlidersHorizontal class="h-4 w-4" :stroke-width="2.5" />

                <span>Filters</span>

                <span
                    v-if="filterCount"
                    class="flex h-5 min-w-5 items-center justify-center rounded-md bg-black px-1.5 font-mono text-[10px] font-extrabold text-white dark:bg-white dark:text-black"
                >
                    {{ filterCount }}
                </span>
            </Button>
        </DropdownMenuTrigger>

        <DropdownMenuContent
            align="end"
            side="bottom"
            :side-offset="8"
            class="w-[min(560px,calc(100vw-2rem))] rounded-2xl border-2 border-black bg-light-primary p-0 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] dark:border-white dark:bg-dark-secondary"
        >
            <!-- Header -->
            <div class="flex items-center justify-between px-4 py-3">
                <div>
                    <p
                        class="font-mono text-xs font-bold uppercase tracking-[0.2em] text-black/40 dark:text-white/40"
                    >
                        Refine results
                    </p>
                    <DropdownMenuLabel class="p-0 text-sm font-extrabold text-black dark:text-white">
                        Filters
                    </DropdownMenuLabel>
                </div>

                <Button
                    v-if="filterCount"
                    type="button"
                    variant="ghost"
                    class="h-7 rounded-lg px-2 text-[11px] font-bold text-black/50 hover:bg-black/5 hover:text-black dark:text-white/50 dark:hover:bg-white/10 dark:hover:text-white"
                    @click="clearFilters"
                >
                    Clear all
                </Button>
            </div>

            <DropdownMenuSeparator class="m-0 bg-black/10 dark:bg-white/10" />

            <!-- Filter groups -->
            <div class="grid grid-cols-1 gap-0 sm:grid-cols-2">
                <!-- States -->
                <div class="min-w-0 border-b border-black/10 sm:border-b-0 sm:border-r dark:border-white/10">
                    <div class="px-4 py-3">
                        <div class="flex items-center justify-between">
                            <span
                                class="font-mono text-[11px] font-bold uppercase tracking-widest text-black/40 dark:text-white/40"
                            >
                                States
                            </span>

                            <span
                                v-if="selectedStates.length"
                                class="font-mono text-[10px] font-bold text-black/40 dark:text-white/40"
                            >
                                {{ selectedStates.length }} selected
                            </span>
                        </div>
                    </div>

                    <div class="max-h-64 overflow-y-auto px-2 pb-2">
                        <DropdownMenuCheckboxItem
                            v-for="state in props.states"
                            :key="state.id"
                            :checked="selectedStates.includes(state.id)"
                            @select.prevent
                            @update:model-value="toggleState(state.id)"
                            class="mx-1 my-0.5 rounded-lg border-2 border-transparent px-3 py-2 text-sm font-bold text-black dark:text-white"
                            :class="{
                                'border-black bg-light-quatrenary dark:border-white dark:bg-dark-tertiary':
                                    selectedStates.includes(state.id),
                            }"
                        >
                            <span class="truncate">{{ state.state }}</span>

                            <template #indicator>
                                <span
                                    v-if="selectedStates.includes(state.id)"
                                    class="ml-auto flex h-5 w-5 shrink-0 items-center justify-center rounded-md bg-black text-white dark:bg-white dark:text-black"
                                >
                                    <Check class="h-3 w-3" :stroke-width="3" />
                                </span>
                            </template>
                        </DropdownMenuCheckboxItem>

                        <p
                            v-if="!props.states?.length"
                            class="rounded-xl border-2 border-dashed border-black/20 p-3 text-center text-xs font-bold text-black/30 dark:border-white/20 dark:text-white/30"
                        >
                            No states available
                        </p>
                    </div>
                </div>

                <!-- Customers -->
                <div class="min-w-0">
                    <div class="px-4 py-3">
                        <div class="flex items-center justify-between">
                            <span
                                class="font-mono text-[11px] font-bold uppercase tracking-widest text-black/40 dark:text-white/40"
                            >
                                Customers
                            </span>

                            <span
                                v-if="selectedCustomers.length"
                                class="font-mono text-[10px] font-bold text-black/40 dark:text-white/40"
                            >
                                {{ selectedCustomers.length }} selected
                            </span>
                        </div>
                    </div>

                    <div class="max-h-64 overflow-y-auto px-2 pb-2">
                        <DropdownMenuCheckboxItem
                            v-for="customer in props.customers"
                            :key="customer.id"
                            :checked="selectedCustomers.includes(customer.id)"
                            @select.prevent
                            @update:model-value="toggleCustomer(customer.id)"
                            class="mx-1 my-0.5 rounded-lg border-2 border-transparent px-3 py-2 text-sm font-bold text-black dark:text-white"
                            :class="{
                                'border-black bg-light-quatrenary dark:border-white dark:bg-dark-tertiary':
                                    selectedCustomers.includes(customer.id),
                            }"
                        >
                            <span class="truncate">{{ customer.name }}</span>

                            <template #indicator>
                                <span
                                    v-if="selectedCustomers.includes(customer.id)"
                                    class="ml-auto flex h-5 w-5 shrink-0 items-center justify-center rounded-md bg-black text-white dark:bg-white dark:text-black"
                                >
                                    <Check class="h-3 w-3" :stroke-width="3" />
                                </span>
                            </template>
                        </DropdownMenuCheckboxItem>

                        <p
                            v-if="!props.customers?.length"
                            class="rounded-xl border-2 border-dashed border-black/20 p-3 text-center text-xs font-bold text-black/30 dark:border-white/20 dark:text-white/30"
                        >
                            No customers available
                        </p>
                    </div>
                </div>
            </div>

            <DropdownMenuSeparator class="m-0 bg-black/10 dark:bg-white/10" />

            <!-- Footer -->
            <div class="flex items-center justify-between px-4 py-3">
                <span class="text-xs font-bold text-black/40 dark:text-white/40">
                    {{ filterCount ? `${filterCount} active filter${filterCount === 1 ? '' : 's'}` : 'No filters selected' }}
                </span>

                <span
                    class="font-mono text-[10px] font-bold uppercase tracking-widest text-black/30 dark:text-white/30"
                >
                    Select to filter
                </span>
            </div>
        </DropdownMenuContent>
    </DropdownMenu>
</template>