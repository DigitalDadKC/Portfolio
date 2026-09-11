<script setup lang="ts">
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/components/ui/select";

const model = defineModel<number>();

const updateModel = (value: string) => {
    model.value = Number(value);
};
</script>

<template>
    <Select
        id="pages"
        :model-value="String(model)"
        @update:model-value="updateModel"
    >
        <SelectTrigger
            class="h-8 w-[72px] rounded-lg border-2 border-black bg-white px-2.5 font-mono text-xs font-bold text-black shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-[transform,background-color] duration-200 hover:-translate-y-0.5 hover:bg-light-quatrenary focus:ring-0 focus:ring-offset-0 dark:border-white dark:bg-black/20 dark:text-white dark:hover:bg-dark-tertiary"
        >
            <SelectValue :placeholder="String(model)" />
        </SelectTrigger>

        <SelectContent
            class="rounded-xl border-2 border-black bg-light-primary text-black shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] dark:border-white dark:bg-dark-primary dark:text-white"
        >
            <SelectGroup>
                <SelectItem
                    value="10"
                    class="rounded-lg font-mono text-xs font-bold focus:bg-light-quatrenary focus:text-black dark:focus:bg-dark-tertiary dark:focus:text-white"
                >
                    10
                </SelectItem>

                <SelectItem
                    value="25"
                    class="rounded-lg font-mono text-xs font-bold focus:bg-light-quatrenary focus:text-black dark:focus:bg-dark-tertiary dark:focus:text-white"
                >
                    25
                </SelectItem>

                <SelectItem
                    value="50"
                    class="rounded-lg font-mono text-xs font-bold focus:bg-light-quatrenary focus:text-black dark:focus:bg-dark-tertiary dark:focus:text-white"
                >
                    50
                </SelectItem>

                <SelectItem
                    value="100"
                    class="rounded-lg font-mono text-xs font-bold focus:bg-light-quatrenary focus:text-black dark:focus:bg-dark-tertiary dark:focus:text-white"
                >
                    100
                </SelectItem>
            </SelectGroup>
        </SelectContent>
    </Select>
</template>
