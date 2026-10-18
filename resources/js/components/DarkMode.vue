<script setup lang="ts">
import { useDark, useToggle } from "@vueuse/core";
import { Sun, Moon } from "lucide-vue-next";
import { useScrolled } from "@/composables/useScrolled";

const { scrolled } = useScrolled();

const isDark = useDark();
const toggleDark = useToggle(isDark);

const swapActive =
    "[transition:opacity_180ms_ease,transform_280ms_cubic-bezier(0.34,1.56,0.64,1)]! motion-reduce:[transition:none]!";
const swapEnterFrom = "opacity-0 [transform:rotate(-90deg)_scale(0.4)]";
const swapLeaveTo = "opacity-0 [transform:rotate(90deg)_scale(0.4)]";
</script>

<template>
    <button
        class="
            group relative
            flex h-10 w-12 shrink-0
            cursor-pointer items-center justify-center
            overflow-hidden
            rounded-xl
            border-2
            transition-[scale,background-color,border-color,box-shadow]
            duration-500
            ease-in-out
            active:scale-95
            focus-visible:outline-2
            focus-visible:outline-offset-2
            focus-visible:outline-accent
            motion-reduce:transition-none
        "
        :class="
            scrolled
                ? 'border-black bg-light-tertiary shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] dark:border-white'
                : 'border-transparent bg-transparent shadow-none'
        "
        @click="toggleDark()"
        aria-label="Switch dark/light mode"
    >
        <span
            aria-hidden="true"
            class="
                absolute inset-0
                bg-black dark:bg-white
                [clip-path:circle(0%_at_50%_50%)]
                transition-[clip-path,background-color] duration-500 ease-in-out
                group-hover:[clip-path:circle(75%_at_50%_50%)]
                group-focus-visible:[clip-path:circle(75%_at_50%_50%)]
                motion-reduce:transition-none
            "
        />

        <Transition
            mode="out-in"
            :enter-active-class="swapActive"
            :leave-active-class="swapActive"
            :enter-from-class="swapEnterFrom"
            :leave-to-class="swapLeaveTo"
        >
            <Sun
                v-if="isDark"
                key="sun"
                class="relative z-10 h-5 w-5 transition-[transform,translate,scale,rotate,color] duration-500 ease-[cubic-bezier(0.34,1.56,0.64,1)] group-hover:rotate-45 group-hover:text-black group-focus-visible:text-black"
                :stroke-width="2.5"
            />
            <Moon
                v-else
                key="moon"
                class="relative z-10 h-5 w-5 transition-[transform,translate,scale,rotate,color] duration-500 ease-[cubic-bezier(0.34,1.56,0.64,1)] group-hover:-rotate-12 group-hover:text-white group-focus-visible:text-white"
                :stroke-width="2.5"
            />
        </Transition>
    </button>
</template>