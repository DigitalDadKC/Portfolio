<script setup lang="ts">
interface Props {
    class?: string
}

const props = defineProps<Props>()

</script>

<template>
    <div
        :class="[
            'pointer-events-none absolute rounded-full',
            props.class
        ]"
    />
</template>