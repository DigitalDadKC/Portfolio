<template>
    <footer class="bg-light-tertiary dark:bg-dark-tertiary py-12">
        <div class="container mx-auto" v-motion :initial="{opacity: 0, y:100}" :visibleOnce="{opacity: 1, y: 0}">
            <div class="flex flex-col md:flex-row space-y-6 lg:space-y-0 items-center md:justify-end">
                <div class="flex items-center justify-center md:mr-10">
                    <img src="img/dad.png" alt="Hero" class="rounded-lg h-16" />
                </div>
                <p class="text-accent opacity-80 text-[15px]">
                    &copy; 2025 digitaldadkc<br /> All rights reserved
                </p>
            </div>
        </div>
    </footer>
</template>v
