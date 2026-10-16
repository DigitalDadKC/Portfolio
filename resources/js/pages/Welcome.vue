<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import FrontendLayout from '@/layouts/Frontend.vue';
import Hero from './portfolio/Hero.vue';
import Promote from './portfolio/Promote.vue';
import About from './portfolio/About.vue';
import Skills from './portfolio/Skills.vue';
import Services from './portfolio/Services.vue';
import Contact from './portfolio/Contact.vue';
import Features from './portfolio/Features.vue';
import Calendar from './portfolio/Calendar.vue';
import Projects from './portfolio/Projects.vue';
import Questions from './portfolio/Questions.vue';

defineProps({
    skills: Object,
    projects: Object,
    features: Object,
})

</script>

<template>
    <Head />

    <FrontendLayout>
        <Hero />
        <!-- <Calendar /> -->
        <Promote />
        <Services />
        <Skills :skills="skills" />
        <Projects :projects :skills />
        <Features :features />
        <!-- <Questions /> -->
        <About />
        <Contact />
    </FrontendLayout>

</template>
