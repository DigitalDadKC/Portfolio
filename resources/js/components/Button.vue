<script setup lang="ts">

</script>

<template>
    <button
        class="
            hidden
            items-center
            justify-center
            rounded-xl
            cursor-pointer
            border-2 border-black
            bg-white/70
            px-5 py-2.5
            text-sm
            font-extrabold
            text-black
            transition-[transform,background-color]
            duration-200
            hover:-translate-y-1
            hover:bg-light-quatrenary
            md:inline-flex
            dark:border-white
            dark:bg-dark-quatrenary
            dark:text-accent
            dark:hover:text-dark-quatrenary
            dark:hover:bg-dark-tertiary
        "
    >
        <slot />
    </button>
</template>