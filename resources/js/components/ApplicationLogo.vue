<script setup lang="ts">

defineProps({
    scrollBackground: {
        type: Boolean,
        default: false,
    },
})
</script>

<template>
    <img
        src="/img/dad.svg"
        alt="DigitalDadKC logo"
        class="
            block
            h-8
            w-8
            shrink-0
            object-contain
            transform-gpu
            transition-transform
            duration-500
            ease-in-out
            will-change-transform
        "
        :class="scrollBackground ? 'scale-70' : 'scale-90'"
        v-motion
        :initial="{ x: 0, y: 0 }"
        :enter="{
            x: Math.floor(Math.random() * 3) - 1,
            y: Math.floor(Math.random() * 3) - 1,
            transition: {
                repeat: Infinity,
                repeatType: 'loop',
                repeatDelay: 100
            }
        }"
    />
</template>