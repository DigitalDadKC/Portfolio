<script setup>
import Project from './Project.vue';
import { ref } from 'vue';

    const props = defineProps({
        skills: Object,
        projects: Object
    })

const filteredProjects = ref(props.projects)
const activeSkill = ref('all')
const filterProjects = (id) => {
    if (id === 'all') {
        filteredProjects.value = props.projects
            activeSkill.value = 'all'
    } else {
        filteredProjects.value = props.projects.filter((project) => {
        return project.skills.map((skill) => skill.id).includes(id);
        })
        activeSkill.value = id
    }
}
</script>

<template>
    <div class="container mx-auto">
        <nav class="mb-12 border-b-2 border-light-tertiary dark:text-dark-tertiary">
            <ul class="flex flex-col sm:flex-row sm:flex-wrap justify-evenly items-center gap-4">
                <li class="cursor-pointer capitalize">
                    <button @click="filterProjects('all')" class="flex text-center py-2 hover:text-accent dark:hover:text-dark-quatrenary font-bold" :class="[activeSkill === 'all' ? 'text-accent dark:text-white' : '' ]">
                        All
                    </button>
                </li>
                <li class="cursor-pointer capitalize" v-for="projectsSkill in skills" :key="projectsSkill.id" @click="filterProjects(projectsSkill.id)">
                    <button class="flex text-center lg:py-2 hover:text-accent dark:hover:text-dark-quatrenary font-bold" :class="[activeSkill === projectsSkill.id ? 'text-accent dark:text-white' : '']">
                        {{ projectsSkill.name }}
                    </button>
                </li>   
            </ul>
        </nav>
        <section class="grid gap-y-12 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 lg:gap-8">
            <Project v-for="project in filteredProjects" :key="project.id" :project="project" />
        </section>
    </div>
</template>