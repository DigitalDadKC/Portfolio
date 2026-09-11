<script setup lang="ts">
import { computed, ref  } from 'vue'
import { router } from '@inertiajs/vue3'
import { Label } from '@/components/ui/label'
import { Button } from '@/components/ui/button'
import { Trash2, Plus } from 'lucide-vue-next'
import Line from './Line.vue'
import FormattedInput from '@/components/FormattedInput.vue'
import draggable from 'vuedraggable'

const props = defineProps({
    index: Number,
    scope: Object,
    unit_of_measurements: Object,
})

const isUpdating = ref(false)

const updateScope = () => {
    isUpdating.value = true

    router.put(route('scopes.update', props.scope.id), {
        name: props.scope.name,
        area: props.scope.area,
    }, {
        preserveScroll: true,

        onFinish: () => {
            isUpdating.value = false
        },
    })
}

const removeScope = () => {
    router.delete(route('scopes.destroy', props.scope.id), {
        preserveScroll: true,
    })
}

const addLine = () => {
    router.post(route('lines.create', props.scope.id), {}, {
        preserveScroll: true,
    })
}

const sort = (scope) => {
    router.patch(route('lines.sort', scope.id), {
        lines: scope.lines,
    }, {
        preserveScroll: true,
    })
}

const total = computed(() => {
    return props.scope.lines.reduce(
        (a, b) => a + (b.price * b.quantity),
        0
    )
})

const linesDisabled = computed(() => {
    return isUpdating.value
})

const eyebrowClass =
    'font-mono text-[11px] font-bold uppercase tracking-widest text-black/40 dark:text-white/40'

const fieldClass =
    'rounded-lg border-2 border-black bg-white font-bold text-black shadow-none dark:border-white dark:bg-black/20 dark:text-white'
</script>

<template>
    <div
        class="relative overflow-hidden rounded-2xl border-2 border-black bg-light-primary shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] dark:border-white dark:bg-dark-tertiary"
        v-motion-slide-right
    >
        <!-- Scope header -->
        <div
            class="flex flex-col gap-4 border-b-2 border-black/10 p-5 dark:border-white/10 sm:flex-row sm:items-center sm:justify-between"
        >
            <div class="flex items-center gap-3">
                <span
                    class="inline-flex items-center rounded-lg border-2 border-black bg-black px-2.5 py-1 font-mono text-xs font-bold tracking-wide text-white dark:border-white dark:bg-white dark:text-black"
                >
                    Scope #{{ props.index + 1 }}
                </span>

                <span
                    :class="eyebrowClass"
                >
                    Work scope
                </span>
            </div>

            <Button
                type="button"
                class="h-8 shrink-0 gap-1.5 rounded-lg border-2 border-black bg-white px-2.5 text-xs font-bold text-black shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-[transform,background-color] duration-200 hover:-translate-y-0.5 hover:bg-red-50 dark:border-white dark:bg-black/20 dark:text-white dark:hover:bg-red-950/40"
                @click.prevent="removeScope()"
            >
                <Trash2 class="h-3.5 w-3.5" :stroke-width="2.5" />
                <span class="hidden sm:inline">Delete scope</span>
                <span class="sm:hidden">Delete</span>
            </Button>
        </div>

        <!-- Scope details -->
        <div class="p-5">
            <div class="mb-5 grid grid-cols-1 gap-4 md:grid-cols-[minmax(0,1fr)_12rem]">
                <div>
                    <Label
                        :for="`scope-name-${props.index}`"
                        :class="[eyebrowClass, 'mb-2 block']"
                    >
                        Scope name
                    </Label>

                    <FormattedInput
                        width="full"
                        :id="`scope-name-${props.index}`"
                        v-model="scope.name"
                        :class="fieldClass"
                        :disabled="isUpdating"
                        @blur="updateScope()"
                    />
                </div>

                <div>
                    <Label
                        :for="`scope-area-${props.index}`"
                        :class="[eyebrowClass, 'mb-2 block']"
                    >
                        Scope area
                    </Label>

                    <FormattedInput
                        width="full"
                        :id="`scope-area-${props.index}`"
                        v-model="scope.area"
                        type="number"
                        :class="fieldClass"
                        :disabled="isUpdating"
                        @blur="updateScope()"
                    />
                </div>
            </div>

            <!-- Lines header -->
            <div class="mb-3 flex items-center justify-between">
                <span :class="eyebrowClass">
                    Line items
                </span>

                <Button
                    type="button"
                    :disabled="isUpdating"
                    class="h-8 gap-1.5 rounded-lg border-2 border-black bg-white px-3 text-xs font-bold text-black shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-[transform,background-color] duration-200 hover:-translate-y-0.5 hover:bg-light-quatrenary disabled:cursor-not-allowed disabled:opacity-50 dark:border-white dark:bg-black/20 dark:text-white dark:hover:bg-dark-tertiary"
                    @click.prevent="addLine()"
                >
                    <Plus class="h-3.5 w-3.5" :stroke-width="3" />
                    Add line
                </Button>
            </div>

            <!-- Lines -->
            <div
                class="overflow-hidden rounded-xl border-2 border-black bg-white dark:border-white dark:bg-black/20"
            >
                <draggable
                    :list="scope.lines"
                    item-key="id"
                    handle=".handle"
                    @change="sort(scope)"
                >
                    <template #item="{ element, index }">
                        <Line
                            :element
                            :index
                            :unit_of_measurements
                            :parent-updating="linesDisabled"
                        />
                    </template>
                </draggable>

                <div
                    v-if="!scope.lines.length"
                    class="border-t border-black/10 px-4 py-6 text-center text-xs font-bold text-black/30 dark:border-white/10 dark:text-white/30"
                >
                    No line items yet — add one to start building this scope
                </div>
            </div>

            <!-- Scope total -->
            <div class="mt-5 flex justify-end">
                <div
                    class="rounded-xl border-2 border-black bg-white px-4 py-3 text-right dark:border-white dark:bg-black/20"
                >
                    <p :class="eyebrowClass">
                        Scope total
                    </p>

                    <FormattedInput
                        v-model="total"
                        width="36"
                        type="currency"
                        :disabled="true"
                        class="mt-0.5 border-0 bg-transparent p-0 text-right font-mono text-xl font-extrabold tabular-nums text-black shadow-none focus:ring-0 dark:bg-transparent dark:text-white"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
