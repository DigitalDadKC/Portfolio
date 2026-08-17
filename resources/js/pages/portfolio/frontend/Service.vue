<script setup>
defineProps({
    service: {
        type: Object,
        required: true,
    },
    delay: {
        type: Number,
        default: 0,
    },
})
</script>

<template>
    <article
        v-motion
        :initial="{ opacity: 0, y: 24 }"
        :visibleOnce="{ opacity: 1, y: 0 }"
        :delay
        class="
            service-card
            group
            relative
            rounded-2xl
            border-2 border-black
            bg-light-tertiary
            p-6
            text-black
            shadow-[5px_5px_0px_0px_rgba(0,0,0,1)]
            transition-[transform,box-shadow,background-color]
            duration-200
            ease-out
            hover:-translate-y-1
            hover:shadow-[8px_8px_0px_0px_rgba(0,0,0,1)]
            dark:border-white
            dark:bg-dark-tertiary
            dark:text-white
        "
    >
        <!-- Icon -->
        <div
            class="
                mb-5
                flex h-12 w-12
                items-center justify-center
                rounded-xl
                border-2 border-black
                bg-light-quatrenary
                transition-transform
                duration-200
                ease-out
                group-hover:rotate-[-6deg]
                group-hover:scale-110
                dark:border-white
            "
        >
            <component
                :is="service.icon"
                class="
                    h-6 w-6
                    transition-transform
                    duration-300
                    ease-out
                    group-hover:rotate-6
                "
            />
        </div>

        <!-- Title -->
        <h3
            class="
                mb-3
                text-xl
                font-extrabold
                tracking-tight
            "
        >
            {{ service.title }}
        </h3>

        <!-- Description -->
        <p
            class="
                text-sm
                leading-6
                text-gray-600
                dark:text-gray-300
            "
        >
            {{ service.detail }}
        </p>
    </article>
</template>