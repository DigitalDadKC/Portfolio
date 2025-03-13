<script setup>
const props = defineProps({
    links: Object,
})

</script>

<template>
    <Component
        :is="link.url ? 'Link' : 'span'"
        v-for="link in props.links"
        :href="link.url"
        v-html="link.label"
        class="px-1"
        :class="{'text-grey-darken-2': !link.url, 'font-bold text-grey-darken-4': link.active}"
    />
</template>
