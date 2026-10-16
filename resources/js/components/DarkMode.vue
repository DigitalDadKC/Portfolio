<script setup lang="ts">
import { useDark, useToggle } from "@vueuse/core";
import { Sun, Moon } from 'lucide-vue-next';
import Button from "./Button.vue";

const isDark = useDark();
const toggleDark = useToggle(isDark);

</script>

<template>
    <Button
        @click="toggleDark()"
        aria-label="Switch dark/light mode"
    >
        <Transition name="icon" mode="out-in">
        <Sun
            v-if="isDark"
            key="sun"
            class="
                h-5 w-5
                transition-transform duration-300
                ease-[cubic-bezier(0.34,1.56,0.64,1)]
                group-hover:rotate-45
                group-hover:scale-125
            "
            :stroke-width="2.5"
        />
        <Moon
            v-else
            key="moon"
            class="
                h-5 w-5
                transition-transform duration-300
                ease-[cubic-bezier(0.34,1.56,0.64,1)]
                group-hover:-rotate-12
                group-hover:scale-125
            "
            :stroke-width="2.5"
        />
        </Transition>
    </Button>
</template>

<style scoped>
.icon-enter-active,
.icon-leave-active {
  transition:
    opacity 180ms ease,
    transform 280ms cubic-bezier(0.34, 1.56, 0.64, 1);
}

.icon-enter-from {
  opacity: 0;
  transform: rotate(-90deg) scale(0.4);
}

.icon-leave-to {
  opacity: 0;
  transform: rotate(90deg) scale(0.4);
}

@media (prefers-reduced-motion: reduce) {
  .icon-enter-active,
  .icon-leave-active {
    transition-duration: 0.01ms;
  }
}
</style>