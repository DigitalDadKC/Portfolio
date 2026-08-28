<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { useDark, useToggle } from "@vueuse/core";
import { Link } from '@inertiajs/vue3';
import ApplicationLogo from '@/components/ApplicationLogo.vue';
import { Sun, Moon, Menu, X, ArrowUpRight } from 'lucide-vue-next';

const isDark = useDark();
const toggleDark = useToggle(isDark);

const showMobileMenu = ref(false);
const scrollBackground = ref(false);

const navigations = [
    { name: "Home", href: "/#home" },
    { name: "Services", href: "/#services" },
    { name: "Portfolio", href: "/#portfolio" },
    { name: "About", href: "/#about" },
    { name: "Contact", href: "/#contact" },
];

const handleScroll = () => {
    scrollBackground.value = window.scrollY > 50;
};

onMounted(() => {
    handleScroll();
    window.addEventListener("scroll", handleScroll, { passive: true });
});

onUnmounted(() => {
    window.removeEventListener("scroll", handleScroll);
});
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
        "
        :class="scrollBackground
            ? 'border-b-2 border-black dark:border-white'
            : 'border-b-2 border-transparent'"
    >
        <!-- Header content -->
        <div
            class="
                origin-top
                transition-transform
                duration-500
                ease-in-out
                will-change-transform
            "
            :class="scrollBackground ? 'scale-[0.96]' : 'scale-100'"
        >
            <div class="container mx-auto flex items-center justify-between px-4 py-3">

                <!-- Logo -->
                <Link href="/" class="group flex items-center gap-3">
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
                        <ApplicationLogo :scrollBackground="scrollBackground" />
                    </div>

                    <span
                        class="
                            self-center
                            whitespace-nowrap
                            text-lg
                            font-extrabold
                            tracking-tight
                            text-black
                            dark:text-white
                        "
                    >
                        Digital<span class="text-accent"> Dad</span>, LLC
                    </span>
                </Link>

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

                    <!-- Dark mode toggle -->
                    <button
                        @click="toggleDark()"
                        aria-label="switch dark/light mode"
                        class="
                            group
                            cursor-pointer
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
                            hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]
                            dark:border-white
                            dark:bg-black/20
                            dark:hover:bg-dark-tertiary
                        "
                    >
                        <Sun v-if="isDark" class="h-5 w-5" :stroke-width="2.5" />
                        <Moon v-else class="h-5 w-5" :stroke-width="2.5" />
                    </button>

                    <!-- Login / Dashboard -->
                    <a
                        v-if="!$page.props.auth.user"
                        :href="route('admin.dashboard')"
                        aria-label="login"
                        aria-current="page"
                        class="
                            hidden
                            items-center
                            justify-center
                            rounded-xl
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
                            dark:bg-black/20
                            dark:text-white
                            dark:hover:bg-dark-tertiary
                        "
                    >
                        Login
                    </a>

                    <a
                        v-else
                        :href="route('admin.dashboard')"
                        aria-label="dashboard"
                        aria-current="page"
                        class="
                            group
                            hidden
                            items-center
                            justify-center
                            gap-2
                            rounded-xl
                            border-2 border-black
                            bg-black
                            px-5 py-2.5
                            text-sm
                            font-extrabold
                            text-white
                            shadow-[3px_3px_0px_0px_rgba(0,0,0,1)]
                            transition-[transform,box-shadow,background-color,color]
                            duration-200
                            hover:-translate-y-1
                            hover:bg-accent
                            hover:text-black
                            hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]
                            md:inline-flex
                            dark:border-white
                            dark:bg-white
                            dark:text-black
                            dark:hover:bg-accent
                        "
                    >
                        Dashboard
                        <ArrowUpRight
                            class="
                                h-4 w-4
                                transition-transform
                                duration-200
                                group-hover:translate-x-1
                                group-hover:-translate-y-1
                            "
                            :stroke-width="2.5"
                        />
                    </a>

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