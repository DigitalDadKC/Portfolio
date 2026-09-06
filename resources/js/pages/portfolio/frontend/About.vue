<template>
    <section
        id="about"
        class="
            section
            relative
            overflow-hidden
            bg-light-secondary
            py-20
            dark:bg-dark-primary
        "
    >
        <!-- Decorative background -->
        <div
            class="
                pointer-events-none
                absolute -right-32 top-20
                h-80 w-80
                rounded-full
                border-2 border-black/10
                dark:border-white/10
            "
        />

        <div
            class="
                pointer-events-none
                absolute -bottom-40 -left-32
                h-96 w-96
                rounded-full
                bg-light-quatrenary/20
                dark:bg-dark-tertiary/20
            "
        />

        <div
            class="relative container mx-auto px-4"
            v-motion
            :initial="{ opacity: 0, y: 24 }"
            :visibleOnce="{ opacity: 1, y: 0 }"
        >
            <div
                class="
                    grid
                    items-center
                    gap-10
                    lg:grid-cols-[0.8fr_1.2fr]
                    lg:gap-16
                "
            >

                <!-- Profile -->
                <div class="group relative mx-auto w-full max-w-md">
                    <!-- Offset shadow -->
                    <div
                        class="
                            absolute
                            inset-0
                            translate-x-2
                            translate-y-2
                            rounded-2xl
                            border-2 border-black
                            bg-light-quatrenary
                            transition-transform
                            duration-300
                            group-hover:translate-x-3
                            group-hover:translate-y-3
                            dark:border-white
                        "
                    />

                    <div
                        class="
                            relative
                            overflow-hidden
                            rounded-2xl
                            border-2 border-black
                            bg-white
                            dark:border-white
                            dark:bg-black/20
                        "
                    >
                        <img
                            src="/img/profile.jpg"
                            alt="Profile"
                            class="
                                aspect-[4/5]
                                w-full
                                object-cover
                                transition-transform
                                duration-500
                                ease-out
                                group-hover:scale-105
                            "
                        />

                        <!-- Image overlay -->
                        <div
                            class="
                                pointer-events-none
                                absolute inset-0
                                bg-gradient-to-t
                                from-black/20
                                via-transparent
                                to-transparent
                            "
                        />
                    </div>

                    <!-- Small badge -->
                    <div
                        class="
                            absolute
                            -bottom-4
                            -right-4
                            rounded-xl
                            border-2 border-black
                            bg-white
                            px-4 py-3
                            font-bold
                            shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]
                            dark:border-white
                            dark:bg-black
                        "
                    >
                        <span class="text-accent">10+</span>
                        years of experience
                    </div>
                </div>

                <!-- Content -->
                <div>
                    <!-- Label -->
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
                        About me
                    </span>

                    <!-- Heading -->
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
                        My journey
                        <span class="text-accent">so far.</span>
                    </h2>

                    <!-- Role -->
                    <div class="mt-5 flex flex-wrap items-center gap-3">
                        <span
                            class="
                                rounded-full
                                border-2 border-black
                                bg-black
                                px-4 py-1.5
                                text-sm font-bold
                                text-white
                                dark:border-white
                                dark:bg-white
                                dark:text-black
                            "
                        >
                            Freelance web developer
                        </span>

                        <span
                            class="
                                text-sm
                                font-medium
                                text-gray-500
                                dark:text-gray-400
                            "
                        >
                            Building useful things for real people.
                        </span>
                    </div>

                    <!-- Divider -->
                    <div
                        class="
                            my-8
                            h-1
                            w-16
                            bg-black
                            dark:bg-white
                        "
                    />

                    <!-- Story -->
                    <div
                        class="
                            space-y-6
                            text-base
                            leading-7
                            text-gray-600
                            dark:text-gray-300
                        "
                    >
                        <p>
                            Father of 3 beautiful girls, husband to a wonderful
                            wife, I have grinded in both the construction and
                            technology industries for over 10 years. Coupled
                            with a unique perspective, a thirst for learning
                            all-things webdev, and powered by
                            <span class="font-bold text-black dark:text-white">
                                java (beans)
                            </span>,
                            I am thrilled to embrace my next software project.
                        </p>

                        <p>
                            My software journey began in the dawn of 2015 when I
                            was inadvertently tasked with improving an Excel
                            spreadsheet and discovered the beauty of what a few
                            lines of code can accomplish. My keen interest
                            spiraled into some intense VBA automation beyond
                            just recording macros...
                            <span class="font-bold text-black dark:text-white">
                                I started to get quite dangerous with it.
                            </span>
                        </p>

                        <p>
                            It didn't take long for me to challenge myself even
                            further and dive into the world of web development.
                            Launching my humble journey with humble beginnings
                            in a basic online resume site to full-scale web
                            applications, I am constantly seeking interesting
                            and challenging projects.
                        </p>
                    </div>

                    <!-- Small footer -->
                    <div
                        class="
                            mt-8
                            flex
                            items-center
                            gap-3
                            text-sm
                            font-bold
                            text-gray-500
                            dark:text-gray-400
                        "
                    >
                        <span
                            class="
                                h-3 w-3
                                rounded-full
                                border-2 border-black
                                bg-accent
                                dark:border-white
                            "
                        />

                        Always learning. Always building.
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>