<template>
    <section
        class="
            relative
            overflow-hidden
            border-y-2 border-black
            bg-light-quatrenary
            py-12
            dark:border-white
            dark:bg-dark-tertiary
            sm:py-16
        "
    >
        <!-- Decorative shapes -->
        <div
            class="
                pointer-events-none
                absolute -left-16 -top-16
                h-40 w-40
                rounded-full
                border-2 border-black/10
                dark:border-white/10
            "
        />

        <div
            class="
                pointer-events-none
                absolute -bottom-20 -right-10
                h-48 w-48
                rounded-full
                bg-black/5
                dark:bg-white/5
            "
        />

        <div
            class="
                relative
                container mx-auto
                flex flex-col
                items-center
                gap-8
                px-4
                text-center
                lg:flex-row
                lg:justify-between
                lg:text-left
            "
            v-motion
            :initial="{ opacity: 0, y: 16 }"
            :visibleOnce="{ opacity: 1, y: 0 }"
        >
            <!-- Text -->
            <div>
                <span
                    class="
                        mb-3
                        inline-block
                        text-sm
                        font-bold
                        uppercase
                        tracking-widest
                        text-black/60
                        dark:text-white/60
                    "
                >
                    Have an idea?
                </span>

                <h2
                    class="
                        text-3xl
                        font-extrabold
                        tracking-tight
                        text-black
                        sm:text-4xl
                        dark:text-white
                    "
                >
                    Ready for your next project?
                    <span
                        class="
                            block
                            text-accent
                        "
                    >
                        Let's build it.
                    </span>
                </h2>
            </div>

            <!-- CTA -->
            <a
                href="#contact"
                class="
                    group
                    inline-flex
                    shrink-0
                    items-center
                    justify-center
                    gap-2
                    rounded-xl
                    border-2 border-black
                    bg-black
                    px-6 py-3
                    text-base
                    font-extrabold
                    text-white
                    shadow-[5px_5px_0px_0px_rgba(0,0,0,1)]
                    transition-[transform,box-shadow,background-color,color]
                    duration-200
                    ease-out
                    hover:-translate-y-1
                    hover:bg-accent
                    hover:text-black
                    hover:shadow-[7px_7px_0px_0px_rgba(0,0,0,1)]
                    active:translate-y-0
                    active:shadow-[3px_3px_0px_0px_rgba(0,0,0,1)]
                    dark:border-white
                    dark:bg-white
                    dark:text-black
                    dark:hover:bg-accent
                "
                aria-label="Go to contact section"
            >
                Get started

                <svg
                    class="
                        h-5 w-5
                        transition-transform
                        duration-200
                        group-hover:translate-x-1
                    "
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2.5"
                    aria-hidden="true"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M5 12h14m-6-6 6 6-6 6"
                    />
                </svg>
            </a>
        </div>
    </section>
</template>