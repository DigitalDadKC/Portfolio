<script setup lang="ts">
import { ref, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';
import ApplicationLogo from '@/components/ApplicationLogo.vue';
import Dropdown from '@/components/Dropdown.vue';
import DropdownLink from '@/components/DropdownLink.vue';
import NavLink from '@/components/NavLink.vue';
import ResponsiveNavLink from '@/components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';
import { NavigationMenu, NavigationMenuContent, NavigationMenuIndicator, NavigationMenuItem, NavigationMenuLink, NavigationMenuList, NavigationMenuTrigger, NavigationMenuViewport, navigationMenuTriggerStyle } from '@/components/ui/navigation-menu'
import DarkMode from '@/components/DarkMode.vue';
import { toast } from 'vue-sonner';
import { Toaster } from '@/components/ui/sonner';

const showingNavigationDropdown = ref(false);
const page = usePage();

watch(
    () => page.props.flash,
    (flash: any) => {
        if (!flash?.message) return;

        const type = ['success', 'error', 'warning', 'info'].includes(flash.type)
            ? flash.type
            : 'success';

        toast[type as 'success' | 'error' | 'warning' | 'info'](flash.message, {
            description: flash.description,
        });
    },
    { immediate: true },
);

const toastOptions = {
    classes: {
        toast: 'rounded-xl! border! border-l-4! shadow-xl! px-4! py-3!',
        title: 'text-sm! font-semibold!',
        description: 'text-xs! opacity-80!',
        closeButton: 'bg-background! border-border!',
        success: 'bg-emerald-50! border-emerald-200! border-l-emerald-500! text-emerald-900! dark:bg-emerald-950! dark:border-emerald-900! dark:text-emerald-100!',
        error: 'bg-red-50! border-red-200! border-l-red-500! text-red-900! dark:bg-red-950! dark:border-red-900! dark:text-red-100!',
        warning: 'bg-amber-50! border-amber-200! border-l-amber-500! text-amber-900! dark:bg-amber-950! dark:border-amber-900! dark:text-amber-100!',
        info: 'bg-blue-50! border-blue-200! border-l-blue-500! text-blue-900! dark:bg-blue-950! dark:border-blue-900! dark:text-blue-100!',
    },
};

</script>

<template>
    <div>
        <div>
            <Toaster
                position="top-center"
                :duration="3500"
                close-button
                :toast-options="toastOptions"
            />
        </div>

        <div class="min-h-screen bg-light-secondary dark:bg-dark-secondary">
            <nav class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <Link :href="route('home')">
                                    <ApplicationLogo
                                        class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                                </Link>
                            </div>

                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <NavigationMenu :viewport="false">
                                    <NavigationMenuList>
                                        <NavigationMenuItem>
                                            <NavigationMenuLink as-child :class="navigationMenuTriggerStyle()">
                                                <NavLink :href="route('admin.dashboard')" :active="route().current('admin.dashboard')">Dashboard</NavLink>
                                            </NavigationMenuLink>
                                        </NavigationMenuItem>
                                        <NavigationMenuItem>
                                            <NavigationMenuLink as-child :class="navigationMenuTriggerStyle()">
                                                <NavLink :href="route('admin.skills.index')" :active="route().current('admin.skills.index')">Skills</NavLink>
                                            </NavigationMenuLink>
                                        </NavigationMenuItem>
                                        <NavigationMenuItem>
                                            <NavigationMenuLink as-child :class="navigationMenuTriggerStyle()">
                                                <NavLink :href="route('admin.projects.index')" :active="route().current('admin.projects.index')">Projects</NavLink>
                                            </NavigationMenuLink>
                                        </NavigationMenuItem>
                                        <NavigationMenuItem>
                                            <NavigationMenuLink as-child :class="navigationMenuTriggerStyle()">
                                                <NavLink :href="route('admin.features.index')" :active="route().current('admin.features.index')">Features</NavLink>
                                            </NavigationMenuLink>
                                        </NavigationMenuItem>
                                        <NavigationMenuItem>
                                            <NavigationMenuTrigger>Business</NavigationMenuTrigger>
                                            <NavigationMenuContent style="overflow: visible">
                                                <ul class="grid w-[140px] gap-4">
                                                    <li>
                                                        <NavLink :href="route('admin.clients.index')" :active="route().current('admin.clients.index')">Clients</NavLink>
                                                    </li>
                                                    <li>
                                                        <NavLink :href="route('admin.services.index')" :active="route().current('admin.services.index')">Services</NavLink>
                                                    </li>
                                                    <li class="relative group/contracts">
                                                        <button type="button" aria-haspopup="true"
                                                            class="flex w-full items-center justify-between text-sm font-medium dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none cursor-pointer">
                                                            Contracts
                                                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd"
                                                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                        </button>
                                                        <div class="hidden group-hover/contracts:block group-focus-within/contracts:block absolute left-full top-0 z-50 pl-4">
                                                            <ul class="grid gap-4 w-max min-w-[140px] p-2 rounded-md border border-gray-100 dark:border-gray-700 bg-light-primary dark:bg-gray-800 shadow-md">
                                                                <li>
                                                                    <NavLink :href="route('admin.contract-template.index')" :active="route().current('admin.contract-template.index')">Template</NavLink>
                                                                </li>
                                                                <li>
                                                                    <NavLink :href="route('admin.contracts.index')" :active="route().current('admin.contracts.index')">Contracts</NavLink>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <NavLink :href="route('admin.invoices.index')" :active="route().current('admin.invoices.index')">Invoices</NavLink>
                                                    </li>
                                                </ul>
                                            </NavigationMenuContent>
                                        </NavigationMenuItem>
                                        <NavigationMenuItem>
                                            <NavigationMenuTrigger>Trading</NavigationMenuTrigger>
                                            <NavigationMenuContent>
                                                <ul class="grid w-[100px] gap-4">
                                                    <li>
                                                        <NavLink :href="route('admin.trades.index')" :active="route().current('admin.trades.index')">Trades</NavLink>
                                                    </li>
                                                </ul>
                                            </NavigationMenuContent>
                                        </NavigationMenuItem>
                                    </NavigationMenuList>
                                </NavigationMenu>
                            </div>
                        </div>

                        <div class="hidden sm:flex sm:items-center sm:ms-6">
                            <DarkMode />
                            <!-- Settings Dropdown -->
                            <div class="ms-3 relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button type="button"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150 cursor-pointer">
                                                {{ $page.props.auth.user.name }}

                                                <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink :href="route('profile.edit')"> Profile </DropdownLink>
                                        <DropdownLink :href="route('logout')" method="post" as="button">
                                            Log Out
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-me-2 flex items-center sm:hidden">
                            <button @click="showingNavigationDropdown = !showingNavigationDropdown"
                                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out cursor-pointer">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path :class="{
                                        hidden: showingNavigationDropdown,
                                        'inline-flex': !showingNavigationDropdown,
                                    }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16" />
                                    <path :class="{
                                        hidden: !showingNavigationDropdown,
                                        'inline-flex': showingNavigationDropdown,
                                    }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
                    class="sm:hidden">
                    <div class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink :href="route('admin.dashboard')" :active="route().current('admin.dashboard')">
                            Dashboard
                        </ResponsiveNavLink>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
                        <div class="px-4">
                            <div class="font-medium text-base text-gray-800 dark:text-gray-200">
                                {{ $page.props.auth.user.name }}
                            </div>
                            <div class="font-medium text-sm text-gray-500">{{ $page.props.auth.user.email }}</div>
                        </div>

                        <div class="mt-3 space-y-1 border-t border-b border-gray-200 dark:border-gray-600">
                            <ResponsiveNavLink :href="route('admin.skills.index')" :active="route().current('admin.skills.index')">
                                Skills
                            </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('admin.projects.index')"
                                :active="route().current('admin.projects.index')">
                                Projects
                            </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('admin.features.index')"
                                :active="route().current('admin.features.index')">
                                Features
                            </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('admin.invoices.index')"
                                :active="route().current('admin.invoices.index')">
                                Invoices
                            </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('admin.clients.index')"
                                :active="route().current('admin.clients.index')">
                                Clients
                            </ResponsiveNavLink>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.edit')"> Profile </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('logout')" method="post" as="button">
                                Log Out
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header class="bg-light-primary dark:bg-dark-primary shadow" v-if="$slots.header">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main class="mt-8">
                <slot />
            </main>
        </div>
    </div>
</template>