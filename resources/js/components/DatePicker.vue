<script setup lang="ts">
import { ref, computed } from 'vue';
import { parseDate, type DateValue } from '@internationalized/date';
import { Button } from '@/components/ui/button';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { Calendar } from '@/components/ui/calendar';
import { CalendarIcon } from '@lucide/vue';
import { useDateFormat } from '@vueuse/core';

const model = defineModel<string | undefined>();
const open = ref(false)

defineProps<{ disabled?: boolean }>();

const calendarValue = computed<DateValue | undefined>({
    get: () => (model.value ? parseDate(model.value) : undefined),
    set: (val) => {
        model.value = val?.toString();
        open.value = false;
    },
});

const formatted = useDateFormat(model, 'MMM DD, YYYY');
</script>

<template>
    <Popover v-model:open="open">
        <PopoverTrigger as-child>
            <Button>
                <CalendarIcon class="mr-2 h-4 w-4" />
                {{ model ? formatted : 'Pick a date' }}
            </Button>
        </PopoverTrigger>

        <PopoverContent class="w-auto p-0">
            <Calendar v-model="calendarValue" initial-focus />
        </PopoverContent>
    </Popover>
</template>