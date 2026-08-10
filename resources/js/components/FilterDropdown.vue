<script setup lang="ts">

const props = defineProps({
    options: Array,
    column: String,
    value: {
        default: 'id',
        type: String,
    },
    returnObject: Boolean,
    returnNumber: {
        type: Boolean,
        default: false,
    },
    disabled: Boolean,
    modelValue: [String, Number, Object],
    showAll: {
        type: Boolean,
        default: true,
    }
});

const emit = defineEmits(['update:modelValue']);

const toggle = (event) => {
    const target = event.target;

    if (target.value === '') {
        emit('update:modelValue', null);
        return;
    }

    if (props.returnObject) {
        emit('update:modelValue', props.options[target.selectedIndex - 1]);
    } else if (props.returnNumber) {
        emit('update:modelValue', Number(target.value));
    } else {
        emit('update:modelValue', target.value);
    }
};
</script>

<template>
    <select
        class="rounded-md border border-black w-full"
        :disabled="disabled"
        :value="modelValue ?? ''"
        @change="toggle"
    >
        <option value="" v-if="props.showAll">All</option>
        <option
            v-for="(option, index) in props.options"
            :key="index"
            :value="returnObject ? option[value] : option"
        >
            {{ returnObject ? option[column] : option }}
        </option>
    </select>
</template>