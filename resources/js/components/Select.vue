<script setup lang="ts">
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue } from "@/components/ui/select"

const model = defineModel()
const props = defineProps({
    options: Object,
    property: String,
})

</script>

<template>
    <Select id="client" onValueChange="{setSelectedValue}" value="{selectedValue}" v-model="model">
        <SelectTrigger class="w-full cursor-pointer bg-white hover:bg-accent hover:text-accent-foreground focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
            <SelectValue placeholder="Select Client" />
        </SelectTrigger>
        <SelectContent>
            <SelectGroup>
                <SelectLabel>Clients</SelectLabel>
                <SelectItem v-for="option in props.options" :key="option.id" :value="option.id">
                    {{ option[props.property] }}
                </SelectItem>
            </SelectGroup>
        </SelectContent>
    </Select>
</template>
