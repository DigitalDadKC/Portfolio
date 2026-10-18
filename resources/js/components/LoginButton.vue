<script setup lang="ts">
import { useScrolled } from "@/composables/useScrolled";

const { scrolled } = useScrolled();
</script>

<template>
    <a
        :href="route('admin.dashboard')"
        :aria-label="$page.props.auth.user ? 'dashboard' : 'login'"
        class="
            group relative
            flex shrink-0 items-center justify-center
            cursor-pointer
            overflow-hidden
            rounded-xl
            border-2
            px-4 py-2
            text-sm font-bold
            text-black dark:text-white
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

        <span
            class="
                relative z-10
                transition-colors duration-500 ease-in-out
                group-hover:text-white
                group-focus-visible:text-white
                dark:group-hover:text-black
                dark:group-focus-visible:text-black
                motion-reduce:transition-none
            "
        >
            {{ $page.props.auth.user ? 'Dashboard' : 'Login' }}
        </span>
    </a>
</template>