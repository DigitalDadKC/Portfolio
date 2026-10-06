<script setup lang="ts">
interface Props {
    class?: string
}

const props = defineProps<Props>()

</script>

<template>
    <div
        :class="[
            'pointer-events-none absolute hidden lg:block h-2 w-2 rounded-full',
            props.class
        ]"
    />
</template>