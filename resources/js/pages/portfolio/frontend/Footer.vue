<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Mail } from 'lucide-vue-next';
import ApplicationLogo from '@/components/ApplicationLogo.vue';

const navigations = [
    { name: "Home", href: "/#home" },
    { name: "Services", href: "/#services" },
    { name: "Portfolio", href: "/#portfolio" },
    { name: "About", href: "/#about" },
    { name: "Contact", href: "/#contact" },
]

const year = new Date().getFullYear()
</script>

<template>
    <footer class="relative overflow-hidden border-t-2 border-black bg-light-tertiary dark:border-white dark:bg-dark-secondary">

        <div
            class="
                pointer-events-none
                absolute -right-24 -top-24
                hidden
                h-72 w-72
                rounded-full
                border-2 border-black/10
                dark:border-white/10
                lg:block
            "
        />

        <div
            class="container relative mx-auto px-4 py-14"
            v-motion
            :initial="{ opacity: 0, y: 40 }"
            :visibleOnce="{ opacity: 1, y: 0 }"
        >
            <div class="grid gap-10 md:grid-cols-[1.3fr_1fr_1fr] md:gap-8">

                <div>
                    <Link href="/" class="group inline-flex items-center gap-3">
                        <div
                            class="
                                flex h-10 w-12 shrink-0
                                items-center justify-center
                                overflow-hidden
                                rounded-xl
                                border-2 border-black
                                bg-light-tertiary
                                shadow-[3px_3px_0px_0px_rgba(0,0,0,1)]
                                transition-transform duration-200
                                group-hover:-translate-y-0.5
                                dark:border-white
                            "
                        >
                            <ApplicationLogo />
                        </div>

                        <span class="text-xl font-extrabold tracking-tight text-black dark:text-white">
                            Digital<span class="text-accent">Dad</span>KC
                        </span>
                    </Link>

                    <p class="mt-4 max-w-xs text-sm font-medium leading-6 text-gray-600 dark:text-gray-300">
                        Transforming spreadsheets and business processes into sophisticated, robust software.
                    </p>
                </div>

                <div>
                    <p class="mb-4 text-sm font-bold uppercase tracking-widest text-gray-500 dark:text-gray-400">
                        Quick links
                    </p>
                    <ul class="space-y-2.5">
                        <li v-for="(navigation, index) in navigations" :key="index">
                            <a
                                :href="navigation.href"
                                class="
                                    text-sm font-bold text-black/70
                                    transition-colors duration-200
                                    hover:text-accent
                                    dark:text-gray-300/70
                                    dark:hover:text-accent
                                "
                            >
                                {{ navigation.name }}
                            </a>
                        </li>
                    </ul>
                </div>

                <div>
                    <p class="mb-4 text-sm font-bold uppercase tracking-widest text-gray-500 dark:text-gray-400">
                        Find me online
                    </p>

                    <div class="flex items-center gap-3">

                        <a
                            href="mailto:raleighgroesbeck@gmail.com"
                            class="
                                group
                                flex h-11 w-11
                                items-center justify-center
                                rounded-xl
                                border-2 border-black
                                bg-white/70
                                shadow-[3px_3px_0px_0px_rgba(0,0,0,1)]
                                transition-[transform,background-color,box-shadow]
                                duration-200
                                hover:-translate-y-1
                                hover:bg-accent
                                dark:hover:bg-accent
                                hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]
                                dark:border-white
                                dark:bg-black/20
                            "
                            aria-label="Email Raleigh"
                        >
                            <Mail class="h-5 w-5" />
                        </a>

                        <a
                            target="_blank"
                            rel="noopener noreferrer"
                            href="https://github.com/DigitalDadKC"
                            class="
                                group
                                flex h-11 w-11
                                items-center justify-center
                                rounded-xl
                                border-2 border-black
                                bg-white/70
                                shadow-[3px_3px_0px_0px_rgba(0,0,0,1)]
                                transition-[transform,background-color,box-shadow]
                                duration-200
                                hover:-translate-y-1
                                hover:bg-accent
                                hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]
                                dark:border-white
                                dark:bg-black/20
                                dark:hover:bg-accent
                            "
                            aria-label="GitHub"
                        >
                            <svg viewBox="0 0 24 24" class="h-5 w-5" fill="currentColor" aria-hidden="true">
                                <path
                                    d="M12 .297c-6.63 0-12 5.373-12 12
                                    0 5.303 3.438 9.8 8.205 11.385
                                    .6.113.82-.258.82-.577
                                    0-.285-.01-1.04-.015-2.04
                                    -3.338.724-4.042-1.61-4.042-1.61
                                    -.546-1.387-1.333-1.756-1.333-1.756
                                    -1.087-.744.084-.729.084-.729
                                    1.205.084 1.838 1.236 1.838 1.236
                                    1.07 1.835 2.809 1.305 3.495.998
                                    .108-.776.417-1.305.76-1.605
                                    -2.665-.3-5.466-1.332-5.466-5.93
                                    0-1.31.465-2.38 1.235-3.22
                                    -.135-.303-.54-1.523.105-3.176
                                    0 0 1.005-.322 3.3 1.23
                                    .96-.267 1.98-.399 3-.405
                                    1.02.006 2.04.138 3 .405
                                    2.28-1.552 3.285-1.23 3.285-1.23
                                    .645 1.653.24 2.873.12 3.176
                                    .765.84 1.23 1.91 1.23 3.22
                                    0 4.61-2.805 5.625-5.475 5.92
                                    .42.36.81 1.096.81 2.22
                                    0 1.606-.015 2.896-.015 3.286
                                    0 .315.21.69.825.57
                                    C20.565 22.092 24 17.592 24 12.297
                                    c0-6.627-5.373-12-12-12"
                                />
                            </svg>
                        </a>

                        <a
                            target="_blank"
                            rel="noopener noreferrer"
                            href="https://www.linkedin.com/in/digitaldad"
                            class="
                                group
                                flex h-11 w-11
                                items-center justify-center
                                rounded-xl
                                border-2 border-black
                                bg-white/70
                                shadow-[3px_3px_0px_0px_rgba(0,0,0,1)]
                                transition-[transform,background-color,box-shadow]
                                duration-200
                                hover:-translate-y-1
                                hover:bg-accent
                                hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]
                                dark:border-white
                                dark:bg-black/20
                                dark:hover:bg-accent
                            "
                            aria-label="LinkedIn"
                        >
                            <svg viewBox="0 0 24 24" class="h-5 w-5" fill="currentColor" aria-hidden="true">
                                <path
                                    d="M20.447 20.452h-3.554v-5.569
                                    c0-1.328-.027-3.037-1.852-3.037
                                    -1.853 0-2.136 1.445-2.136 2.939
                                    v5.667H9.351V8.999h3.414v1.561h.046
                                    c.477-.9 1.637-1.85 3.37-1.85
                                    3.601 0 4.267 2.37 4.267 5.455
                                    v6.287zM5.337 7.433a2.062 2.062 0 1 1
                                    0-4.124 2.062 2.062 0 0 1 0 4.124z
                                    M7.119 20.452H3.555V8.999h3.564v11.453z
                                    M22.225 0H1.771C.792 0 0 .774 0 1.729
                                    v20.542C0 23.227.792 24 1.771 24h20.451
                                    C23.2 24 24 23.227 24 22.271V1.729
                                    C24 .774 23.2 0 22.222 0h.003z"
                                />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <p class="text-sm font-bold text-accent">
                &copy; {{ year }} Digital Dad, LLC — All rights reserved
            </p>
        </div>
    </footer>
</template>