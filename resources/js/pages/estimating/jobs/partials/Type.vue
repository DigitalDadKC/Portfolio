<script setup lang="ts">
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/components/ui/select";

const model = defineModel<string>();

const props = defineProps({
    types: Object,
});
</script>

<template>
    <Select
        id="type"
        v-model="model"
    >
        <SelectTrigger
            class="h-8 w-[150px] rounded-lg border-2 border-black bg-white px-2.5 font-mono text-xs font-bold text-black shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-[transform,background-color] duration-200 hover:-translate-y-0.5 hover:bg-light-quatrenary focus:ring-0 focus:ring-offset-0 dark:border-white dark:bg-black/20 dark:text-white dark:hover:bg-dark-tertiary"
        >
            <SelectValue placeholder="Select type" />
        </SelectTrigger>

        <SelectContent
            class="rounded-xl border-2 border-black bg-light-primary text-black shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] dark:border-white dark:bg-dark-primary dark:text-white"
        >
            <SelectGroup>
                <SelectItem
                    v-for="type in props.types"
                    :key="type"
                    :value="type"
                    class="rounded-lg font-mono text-xs font-bold focus:bg-light-quatrenary focus:text-black dark:focus:bg-dark-tertiary dark:focus:text-white"
                >
                    {{ type }}
                </SelectItem>
            </SelectGroup>
        </SelectContent>
    </Select>
</template>