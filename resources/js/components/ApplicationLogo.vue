<script setup lang="ts">
import { ref, watch, onMounted, onUnmounted } from 'vue'
import { useScrolled } from '@/composables/useScrolled'

const { scrolled } = useScrolled()
const wrapper = ref<HTMLElement | null>(null)

let timer: number | undefined
let running = false

const rand = (min: number, max: number) => min + Math.random() * (max - min)

const twitch = () => {
    if (!running || !wrapper.value) return

    // Mostly small shakes, with an occasional big spike
    const spike = Math.random() < 0.12 ? 1.8 : 1

    wrapper.value.style.setProperty('--jx', String(rand(-1.5, 1.5) * spike))
    wrapper.value.style.setProperty('--jy', String(rand(-1.5, 1.5) * spike))
    wrapper.value.style.setProperty('--jr', String(rand(-3, 3) * spike))

    // Usually fast, but sometimes the cup pauses for a beat
    const rest = Math.random() < 0.1
    timer = window.setTimeout(twitch, rest ? rand(150, 350) : rand(25, 90))
}

const start = () => {
    if (running) return
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return
    running = true
    twitch()
}

const stop = () => {
    running = false
    window.clearTimeout(timer)
}

watch(scrolled, (isScrolled) => (isScrolled ? start() : stop()))

onMounted(() => {
    if (scrolled.value) start()
})

onUnmounted(stop)
</script>

<template>
    <span
        ref="wrapper"
        class="logo-jitter-wrapper flex items-center justify-center"
        :class="{ 'is-jittering': scrolled }"
    >
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
            :class="scrolled ? 'scale-70' : 'scale-90'"
        />
    </span>
</template>

<style scoped>
@property --jitter {
    syntax: '<number>';
    inherits: false;
    initial-value: 0;
}

.logo-jitter-wrapper {
    --jitter: 0;
    --jx: 0;
    --jy: 0;
    --jr: 0;

    transform:
        translate(calc(var(--jitter) * var(--jx) * 1px), calc(var(--jitter) * var(--jy) * 1px))
        rotate(calc(var(--jitter) * var(--jr) * 1deg));

    /* Ramp OUT */
    transition: --jitter 0.9s ease-out, transform 60ms linear;
}

.logo-jitter-wrapper.is-jittering {
    --jitter: 1;
    /* Ramp IN */
    transition: --jitter 0.6s ease-in, transform 60ms linear;
}
</style>