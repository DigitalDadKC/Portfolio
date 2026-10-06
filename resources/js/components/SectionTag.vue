<script setup lang="ts">
interface Props {
    class?: string
}

const props = defineProps<Props>()

</script>

<template>
    <span
        :class="[
            'mb-4 inline-block rounded-full border-2 border-black bg-light-quatrenary px-4 py-1 text-sm font-bold uppercase tracking-widest shadow-[3px_3px_0px_0px_rgba(0,0,0,1)]',
            props.class
        ]"
    >
        <slot />
    </span>
</template>