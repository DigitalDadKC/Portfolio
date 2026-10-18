<script setup lang="ts">
import Header from '@/pages/portfolio/Header.vue';
import Footer from '@/pages/portfolio/Footer.vue';

</script>

<template>
    <div >
        <Header />

        <main class="min-h-screen">
            <slot />
        </main>

        <Footer />
    </div>
</template>
