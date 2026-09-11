<script setup lang="ts">
import { computed } from 'vue'
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select'

const model = defineModel()

const props = defineProps({
    unit_of_measurements: {
        type: Object,
        default: () => [],
    },
    disabled: {
        type: Boolean,
        default: false,
    },
})

const uoms = computed(() => {
    return [
        { id: null, UOM: 'Select' },
        ...props.unit_of_measurements,
    ]
})
</script>

<template>
    <Select
        id="uom"
        v-model="model"
        :disabled="props.disabled"
    >
        <SelectTrigger
            class="w-full rounded-lg border-2 border-black bg-white font-bold text-black shadow-none transition-colors dark:border-white dark:bg-black/20 dark:text-white"
        >
            <SelectValue placeholder="Select UOM" />
        </SelectTrigger>

        <SelectContent>
            <SelectGroup class="bg-light-tertiary dark:bg-dark-primary">
                <SelectItem
                    v-for="uom in uoms"
                    :key="uom.id"
                    :value="uom.id"
                    class="font-medium text-black dark:text-white"
                >
                    {{ uom.UOM }}
                </SelectItem>
            </SelectGroup>
        </SelectContent>
    </Select>
</template>