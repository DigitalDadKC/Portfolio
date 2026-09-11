<script setup lang="ts">
import { computed, ref } from 'vue'
import { router } from '@inertiajs/vue3'
import { GripVertical, Trash2, ChevronDown } from 'lucide-vue-next'
import { Label } from '@/components/ui/label'
import { Button } from '@/components/ui/button'
import FormattedInput from '@/components/FormattedInput.vue'
import Uom from './partials/Uom.vue'

const props = defineProps({
    index: Number,
    element: Object,
    unit_of_measurements: Object,

    /**
     * True when the Scope or Proposal containing this line
     * is currently being saved.
     */
    parentUpdating: {
        type: Boolean,
        default: false,
    },
})

const isUpdating = ref(false)

const disabled = computed(() => {
    return isUpdating.value || props.parentUpdating
})

const updateLine = () => {
    // Don't allow another request while this line or its parent
    // is already being updated.
    if (disabled.value) {
        return
    }

    isUpdating.value = true

    router.put(route('lines.update', props.element.id), {
        description: props.element.description,
        unit_of_measurement_id: props.element.unit_of_measurement.id,
        price: props.element.price,
        quantity: props.element.quantity,
    }, {
        preserveScroll: true,

        onFinish: () => {
            isUpdating.value = false
        },
    })
}

const removeLine = () => {
    if (disabled.value) {
        return
    }

    router.delete(route('lines.destroy', props.element.id), {
        preserveScroll: true,
    })
}

const total = computed(() => {
    return props.element.price * props.element.quantity
})

const eyebrowClass =
    'font-mono text-[10px] font-bold uppercase tracking-widest text-black/40 dark:text-white/40'

const fieldClass =
    'rounded-lg border-2 border-black bg-white font-bold text-black shadow-none dark:border-white dark:bg-black/20 dark:text-white'
</script>

<template>
    <div
        v-motion-slide-right
        class="border-b-2 border-black/10 last:border-b-0 dark:border-white/10"
        :class="{ 'opacity-60': disabled }"
    >
        <!-- Desktop -->
        <div class="hidden md:grid md:grid-cols-[2rem_minmax(0,1fr)_7rem_8rem_7rem_9rem_2rem] md:items-end md:gap-2 md:p-3">

            <!-- Drag handle -->
            <div class="flex h-9 items-center justify-center">
                <GripVertical
                    class="handle h-5 w-5 cursor-grab text-black/30 transition-colors hover:text-black dark:text-white/30 dark:hover:text-white"
                    :class="{ 'cursor-not-allowed': disabled }"
                    :stroke-width="2.5"
                />
            </div>

            <!-- Description -->
            <div class="min-w-0">
                <Label
                    v-if="!index"
                    :class="[eyebrowClass, 'mb-1.5 block']"
                >
                    Description
                </Label>

                <FormattedInput
                    v-model="element.description"
                    width="full"
                    :class="fieldClass"
                    :disabled="disabled"
                    @blur="updateLine()"
                />
            </div>

            <!-- Unit -->
            <div>
                <Label
                    v-if="!index"
                    :class="[eyebrowClass, 'mb-1.5 block']"
                >
                    Unit
                </Label>

                <Uom
                    v-model="element.unit_of_measurement.id"
                    :unit_of_measurements
                    :disabled="disabled"
                    @update:model-value="updateLine()"
                />
            </div>

            <!-- Price -->
            <div>
                <Label
                    v-if="!index"
                    :class="[eyebrowClass, 'mb-1.5 block']"
                >
                    Price
                </Label>

                <FormattedInput
                    v-model="element.price"
                    type="currency"
                    width="full"
                    :class="fieldClass"
                    :disabled="disabled"
                    @blur="updateLine()"
                />
            </div>

            <!-- Quantity -->
            <div>
                <Label
                    v-if="!index"
                    :class="[eyebrowClass, 'mb-1.5 block']"
                >
                    Quantity
                </Label>

                <FormattedInput
                    v-model="element.quantity"
                    type="number"
                    width="full"
                    :class="fieldClass"
                    :disabled="disabled"
                    @blur="updateLine()"
                />
            </div>

            <!-- Total -->
            <div>
                <Label
                    v-if="!index"
                    :class="[eyebrowClass, 'mb-1.5 block']"
                >
                    Total
                </Label>

                <FormattedInput
                    v-model="total"
                    type="currency"
                    width="full"
                    :disabled="true"
                    class="rounded-lg border-2 border-black bg-black/5 font-mono font-extrabold tabular-nums text-black shadow-none dark:border-white dark:bg-white/10 dark:text-white"
                />
            </div>

            <!-- Delete -->
            <div class="flex h-9 items-center justify-center">
                <button
                    type="button"
                    aria-label="Delete line"
                    :disabled="disabled"
                    class="flex h-8 w-8 items-center justify-center rounded-lg border-2 border-black bg-white text-black shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-[transform,background-color] duration-200 hover:-translate-y-0.5 hover:bg-red-50 cursor-pointer disabled:cursor-not-allowed disabled:opacity-40 dark:border-white dark:bg-black/20 dark:text-white dark:hover:bg-red-950/40"
                    @click="removeLine()"
                >
                    <Trash2
                        class="h-3.5 w-3.5"
                        :stroke-width="2.5"
                    />
                </button>
            </div>
        </div>

        <!-- Mobile -->
        <details
            class="group md:hidden"
            :class="{ 'pointer-events-none': disabled }"
        >
            <summary
                class="flex cursor-pointer list-none items-center gap-2 p-3 [&::-webkit-details-marker]:hidden"
            >
                <GripVertical
                    class="handle h-5 w-5 shrink-0 cursor-grab text-black/30 dark:text-white/30"
                    :stroke-width="2.5"
                />

                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-extrabold text-black dark:text-white">
                        {{ element.description || 'Unnamed line item' }}
                    </p>

                    <p class="mt-0.5 font-mono text-[10px] font-bold uppercase tracking-wider text-black/40 dark:text-white/40">
                        {{ element.quantity || 0 }}
                        {{ element.unit_of_measurement?.name || '' }}
                        ·
                        {{ element.price || 0 }}
                    </p>
                </div>

                <span class="shrink-0 font-mono text-sm font-extrabold tabular-nums text-black dark:text-white">
                    {{ total }}
                </span>

                <ChevronDown
                    class="h-4 w-4 shrink-0 text-black/40 transition-transform duration-200 group-open:rotate-180 dark:text-white/40"
                />
            </summary>

            <div class="pointer-events-auto border-t-2 border-black/10 bg-black/[0.02] p-3 dark:border-white/10 dark:bg-white/[0.02]">
                <div class="grid grid-cols-2 gap-3">

                    <!-- Description -->
                    <div class="col-span-2">
                        <Label :class="[eyebrowClass, 'mb-1.5 block']">
                            Description
                        </Label>

                        <FormattedInput
                            v-model="element.description"
                            width="full"
                            :class="fieldClass"
                            :disabled="disabled"
                            @blur="updateLine()"
                        />
                    </div>

                    <!-- Unit -->
                    <div class="col-span-2">
                        <Label :class="[eyebrowClass, 'mb-1.5 block']">
                            Unit
                        </Label>

                        <Uom
                            v-model="element.unit_of_measurement.id"
                            :class="fieldClass"
                            :unit_of_measurements
                            :disabled="disabled"
                            @update:model-value="updateLine()"
                        />
                    </div>

                    <!-- Price -->
                    <div>
                        <Label :class="[eyebrowClass, 'mb-1.5 block']">
                            Price
                        </Label>

                        <FormattedInput
                            v-model="element.price"
                            type="currency"
                            width="full"
                            :class="fieldClass"
                            :disabled="disabled"
                            @blur="updateLine()"
                        />
                    </div>

                    <!-- Quantity -->
                    <div>
                        <Label :class="[eyebrowClass, 'mb-1.5 block']">
                            Quantity
                        </Label>

                        <FormattedInput
                            v-model="element.quantity"
                            type="number"
                            width="full"
                            :class="fieldClass"
                            :disabled="disabled"
                            @blur="updateLine()"
                        />
                    </div>

                    <!-- Total -->
                    <div class="col-span-2">
                        <Label :class="[eyebrowClass, 'mb-1.5 block']">
                            Total
                        </Label>

                        <FormattedInput
                            v-model="total"
                            type="currency"
                            width="full"
                            :disabled="true"
                            class="rounded-lg border-2 border-black bg-black/5 font-mono font-extrabold tabular-nums text-black shadow-none dark:border-white dark:bg-white/10 dark:text-white"
                        />
                    </div>

                    <!-- Delete -->
                    <div class="col-span-2 flex justify-end pt-1">
                        <Button
                            type="button"
                            :disabled="disabled"
                            class="inline-flex h-8 items-center gap-1.5 rounded-lg border-2 border-black bg-white px-2.5 text-xs font-bold text-black shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-[transform,background-color] duration-200 hover:-translate-y-0.5 hover:bg-red-50 disabled:cursor-not-allowed cursor-pointer disabled:opacity-40 dark:border-white dark:bg-black/20 dark:text-white dark:hover:bg-red-950/40"
                            @click="removeLine()"
                        > 
                            <Trash2
                                class="h-3.5 w-3.5 cursor-pointer"
                                :stroke-width="2.5"
                            />
                            Delete line
                        </Button>
                    </div>
                </div>
            </div>
        </details>
    </div>
</template>
