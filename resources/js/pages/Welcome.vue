<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import FrontendLayout from '@/layouts/Frontend.vue';
import Hero from './portfolio/frontend/Hero.vue';
import Promote from './portfolio/frontend/Promote.vue';
import About from './portfolio/frontend/About.vue';
import Skills from './portfolio/frontend/Skills.vue';
import Services from './portfolio/frontend/Services.vue';
import Contact from './portfolio/frontend/Contact.vue';
import Features from './portfolio/frontend/Features.vue';
import Calendar from './portfolio/frontend/Calendar.vue';
import Projects from './portfolio/frontend/Projects.vue';
import Questions from './portfolio/frontend/Questions.vue';

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
        <Calendar />
        <Promote />
        <Services />
        <Skills :skills="skills" />
        <Projects :projects :skills />
        <Features :features />
        <Questions />
        <About />
        <Contact />
    </FrontendLayout>

</template>
