<script setup>
import Project from './Project.vue'
import { computed, ref } from 'vue'

const props = defineProps({
    skills: {
        type: Array,
        default: () => [],
    },
    projects: {
        type: Array,
        default: () => [],
    },
})

const activeSkill = ref('all')

const filteredProjects = computed(() => {
    if (activeSkill.value === 'all') {
        return props.projects
    }

    return props.projects.filter((project) =>
        project.skills?.some((skill) => skill.id === activeSkill.value)
    )
})

const filterProjects = (id) => {
    activeSkill.value = id
}
</script>

<template>
    <section
        id="projects"
        class="
            relative
            overflow-hidden
            py-20
        "
    >
        <!-- Decorative background -->
        <div
            class="
                pointer-events-none
                absolute -right-32 top-10
                h-72 w-72
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

        <div class="relative container mx-auto px-4">

            <!-- Heading -->
            <div class="mb-12 text-center">
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
                    Selected work
                </span>

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
                    Projects I've built
                </h2>

                <p
                    class="
                        mx-auto mt-4
                        max-w-2xl
                        text-gray-600
                        dark:text-gray-300
                    "
                >
                    A selection of applications, tools, and systems
                    built to solve real problems.
                </p>
            </div>

            <!-- Filters -->
            <nav
                class="
                    mb-12
                    flex flex-wrap
                    items-center
                    justify-center
                    gap-3
                "
                aria-label="Filter projects"
            >
                <button
                    type="button"
                    class="
                        project-filter
                        rounded-full
                        border-2 border-black
                        px-4 py-2
                        text-sm font-bold
                        transition-[transform,box-shadow,background-color,color]
                        duration-200
                        ease-out
                        hover:-translate-y-0.5
                    "
                    :class="
                        activeSkill === 'all'
                            ? 'bg-black text-white shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] dark:bg-white dark:text-black'
                            : 'bg-white text-black hover:bg-light-quatrenary dark:bg-black/20 dark:text-white'
                    "
                    @click="filterProjects('all')"
                >
                    All
                </button>

                <button
                    v-for="skill in skills"
                    :key="skill.id"
                    type="button"
                    class="
                        project-filter
                        rounded-full
                        border-2 border-black
                        px-4 py-2
                        text-sm font-bold
                        transition-[transform,box-shadow,background-color,color]
                        duration-200
                        ease-out
                        hover:-translate-y-0.5
                    "
                    :class="
                        activeSkill === skill.id
                            ? 'bg-black text-white shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] dark:bg-white dark:text-black'
                            : 'bg-white text-black hover:bg-light-quatrenary dark:bg-black/20 dark:text-white'
                    "
                    @click="filterProjects(skill.id)"
                >
                    {{ skill.name }}
                </button>
            </nav>

            <!-- Projects -->
            <TransitionGroup
                name="project"
                tag="section"
                class="
                    grid
                    gap-6
                    md:grid-cols-2
                    xl:grid-cols-4
                "
            >
                <Project
                    v-for="project in filteredProjects"
                    :key="project.id"
                    :project="project"
                />
            </TransitionGroup>

            <!-- Empty state -->
            <div
                v-if="filteredProjects.length === 0"
                class="
                    rounded-2xl
                    border-2 border-dashed border-black
                    p-12
                    text-center
                    dark:border-white
                "
            >
                <p
                    class="
                        text-lg
                        font-bold
                        text-gray-600
                        dark:text-gray-300
                    "
                >
                    No projects found for this skill.
                </p>

                <button
                    type="button"
                    class="
                        mt-4
                        font-bold
                        text-accent
                        underline
                        underline-offset-4
                    "
                    @click="filterProjects('all')"
                >
                    View all projects
                </button>
            </div>
        </div>
    </section>
</template>

<style scoped>
.project-enter-active,
.project-leave-active {
    transition:
        opacity 250ms ease,
        transform 250ms ease;
}

.project-enter-from {
    opacity: 0;
    transform: translateY(12px) scale(0.97);
}

.project-leave-to {
    opacity: 0;
    transform: translateY(-8px) scale(0.97);
}

.project-leave-active {
    position: absolute;
}
</style>