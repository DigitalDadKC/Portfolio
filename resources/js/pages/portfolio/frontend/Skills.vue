<script setup>
defineProps({
    skills: {
        type: Array,
        default: () => [],
    },
})
</script>

<template>
    <section
        id="skills"
        class="
            relative
            overflow-hidden
            bg-light-tertiary
            py-20
            dark:bg-dark-quatrenary
        "
    >
        <!-- Decorative background -->
        <div
            class="
                pointer-events-none
                absolute -left-24 top-1/2
                h-64 w-64
                -translate-y-1/2
                rounded-full
                border-2 border-black/10
                dark:border-white/10
            "
        />

        <div
            class="
                pointer-events-none
                absolute -right-24 bottom-0
                h-72 w-72
                rounded-full
                bg-light-quatrenary/20
                dark:bg-dark-tertiary/20
            "
        />

        <div class="relative container mx-auto px-4">

            <!-- Heading -->
            <div class="mb-10 text-center">
                <span
                    class="
                        mb-4 inline-block
                        rounded-full
                        border-2 border-black
                        bg-light-quatrenary
                        px-4 py-1
                        text-sm font-bold
                        uppercase tracking-widest
                        shadow-[3px_3px_0px_0px_rgba(0,0,0,1)]
                    "
                >
                    My toolbox
                </span>

                <h2
                    class="
                        text-4xl
                        font-extrabold
                        tracking-tight
                        text-black
                        dark:text-white
                        sm:text-5xl
                    "
                >
                    Technologies I use
                </h2>

                <p
                    class="
                        mx-auto mt-4
                        max-w-xl
                        text-gray-600
                        dark:text-gray-300
                    "
                >
                    The tools and technologies I use to build,
                    ship, and maintain projects.
                </p>
            </div>

            <!-- Skills -->
            <div
                class="
                    grid
                    grid-cols-2
                    gap-4
                    sm:grid-cols-3
                    md:grid-cols-4
                    lg:grid-cols-6
                "
            >
                <div
                    v-for="skill in skills"
                    :key="skill.id"
                    class="
                        skill-card
                        group
                        flex
                        min-h-32
                        flex-col
                        items-center
                        justify-center
                        gap-3
                        rounded-2xl
                        border-2 border-black
                        bg-white/70
                        p-5
                        shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]
                        transition-[transform,box-shadow,background-color]
                        duration-200
                        ease-out
                        hover:-translate-y-1
                        hover:bg-light-quatrenary
                        hover:shadow-[6px_6px_0px_0px_rgba(0,0,0,1)]
                        dark:border-white
                        dark:bg-black/20
                        dark:hover:bg-dark-tertiary
                    "
                >
                    <img
                        :src="skill.image"
                        :alt="skill.name"
                        class="
                            h-12
                            w-auto
                            object-contain
                            grayscale
                            transition-[filter,transform]
                            duration-300
                            ease-out
                            group-hover:scale-110
                            group-hover:grayscale-0
                            lg:h-16
                        "
                    />

                    <span
                        class="
                            text-sm
                            font-bold
                            text-gray-500
                            transition-colors
                            duration-200
                            group-hover:text-black
                            dark:text-gray-400
                            dark:group-hover:text-white
                        "
                    >
                        {{ skill.name }}
                    </span>
                </div>
            </div>
        </div>
    </section>
</template>