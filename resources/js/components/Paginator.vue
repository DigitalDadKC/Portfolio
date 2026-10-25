<script setup lang="ts">

const props = defineProps({
    links: Object,
})

const emit = defineEmits(['update:model-value'])

</script>

<template>
    <Component
        :is="link.url ? 'Link' : 'span'"
        v-for="link in props.links"
        v-html="link.label"
        class="rounded-md py-2 px-4"
        :class="{'text-light-tertiary dark:text-dark-quatrenary': !link.url, 'font-bold text-light-secondary bg-light-quatrenary dark:bg-dark-quatrenary dark:text-dark-primary': link.active}"
        @click="emit('update:model-value', link.page)"
        prefetch
    />
</template>
