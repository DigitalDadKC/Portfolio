<script setup lang="ts">
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import ApplicationLogo from '@/components/ApplicationLogo.vue';
import { Menu, X } from 'lucide-vue-next';
import DarkMode from '@/components/DarkMode.vue';
import { useScrolled } from '@/composables/useScrolled'
import LoginButton from '@/components/LoginButton.vue';

const { scrolled } = useScrolled()
const showMobileMenu = ref(false);

const navigations = [
    { name: "Home", href: "/#home" },
    { name: "Services", href: "/#services" },
    { name: "Portfolio", href: "/#projects" },
    // { name: "FAQ", href: "/#questions" },
    { name: "About", href: "/#about" },
    { name: "Contact", href: "/#contact" },
];

</script>

<template>
    <nav
        class="
            fixed top-0 z-50
            w-full
            bg-light-primary/80
            backdrop-blur-md
            transition-[background-color,border-color,box-shadow]
            duration-300
            dark:bg-dark-primary/80
            border-b-2
        "
        :class="scrolled
            ? 'border-black dark:border-white bg-light-secondary/80 dark:bg-dark-secondary/80 shadow-md'
            : 'border-transparent'"
    >
        <div
            class="
                origin-top
                transition-transform
                duration-500
                ease-in-out
                will-change-transform
            "
            :class="scrolled ? 'scale-[0.9]' : 'scale-100'"
        >
            <div class="container mx-auto flex items-center justify-between px-4 py-3">

                <ApplicationLogo :scroll-effect="true" />

                <!-- Desktop nav links -->
                <ul class="hidden items-center gap-1 md:flex">
                    <li v-for="(navigation, index) in navigations" :key="index">
                        <a
                            :href="navigation.href"
                            :aria-label="navigation.name"
                            aria-current="page"
                            class="
                                rounded-lg
                                px-4 py-2
                                text-sm
                                font-bold
                                uppercase
                                tracking-widest
                                text-gray-600
                                transition-colors
                                duration-200
                                hover:text-black
                                dark:text-gray-300
                                dark:hover:text-white
                            "
                        >
                            {{ navigation.name }}
                        </a>
                    </li>
                </ul>

                <!-- Right controls -->
                <div class="flex items-center gap-3">
                    <DarkMode />
                    <LoginButton />

                    <!-- Mobile menu toggle -->
                    <button
                        @click="showMobileMenu = !showMobileMenu"
                        type="button"
                        class="
                            flex h-11 w-11
                            items-center justify-center
                            rounded-xl
                            border-2 border-black
                            bg-white/70
                            shadow-[3px_3px_0px_0px_rgba(0,0,0,1)]
                            transition-[transform,background-color,box-shadow]
                            duration-200
                            hover:-translate-y-1
                            hover:bg-light-quatrenary
                            md:hidden
                            dark:border-white
                            dark:bg-black/20
                            dark:hover:bg-dark-tertiary
                        "
                        aria-controls="navbar-default"
                        :aria-expanded="showMobileMenu"
                        aria-label="Open main menu"
                    >
                        <Menu
                            v-if="!showMobileMenu"
                            class="h-5 w-5"
                            :stroke-width="2.5"
                        />
                        <X
                            v-else
                            class="h-5 w-5"
                            :stroke-width="2.5"
                        />
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div
            id="navbar-default"
            v-show="showMobileMenu"
            class="
                border-t-2 border-black
                bg-light-primary
                px-4
                py-4
                md:hidden
                dark:border-white
                dark:bg-dark-primary
            "
        >
            <ul class="flex flex-col gap-2">
                <li v-for="(navigation, index) in navigations" :key="index">
                    <a
                        :href="navigation.href"
                        :aria-label="navigation.name"
                        aria-current="page"
                        @click="showMobileMenu = false"
                        class="
                            block
                            rounded-xl
                            border-2 border-black
                            bg-white/70
                            px-4 py-3
                            text-sm
                            font-extrabold
                            uppercase
                            tracking-widest
                            text-black
                            transition-[transform,background-color]
                            duration-200
                            hover:-translate-y-0.5
                            hover:bg-light-quatrenary
                            dark:border-white
                            dark:bg-black/20
                            dark:text-white
                            dark:hover:bg-dark-tertiary
                        "
                    >
                        {{ navigation.name }}
                    </a>
                </li>

                <li>
                    <a
                        v-if="!$page.props.auth.user"
                        :href="route('admin.dashboard')"
                        aria-label="login"
                        @click="showMobileMenu = false"
                        class="
                            block
                            rounded-xl
                            border-2 border-black
                            bg-black
                            px-4 py-3
                            text-center
                            text-sm
                            font-extrabold
                            text-white
                            shadow-[3px_3px_0px_0px_rgba(0,0,0,1)]
                            transition-[transform,box-shadow,background-color,color]
                            duration-200
                            hover:-translate-y-0.5
                            hover:bg-accent
                            hover:text-black
                            dark:border-white
                            dark:bg-white
                            dark:text-black
                            dark:hover:bg-accent
                        "
                    >
                        Login
                    </a>

                    <a
                        v-else
                        :href="route('admin.dashboard')"
                        aria-label="dashboard"
                        @click="showMobileMenu = false"
                        class="
                            block
                            rounded-xl
                            border-2 border-black
                            bg-black
                            px-4 py-3
                            text-center
                            text-sm
                            font-extrabold
                            text-white
                            shadow-[3px_3px_0px_0px_rgba(0,0,0,1)]
                            transition-[transform,box-shadow,background-color,color]
                            duration-200
                            hover:-translate-y-0.5
                            hover:bg-accent
                            hover:text-black
                            dark:border-white
                            dark:bg-white
                            dark:text-black
                            dark:hover:bg-accent
                        "
                    >
                        Dashboard
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</template>