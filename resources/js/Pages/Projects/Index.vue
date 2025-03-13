<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head, Link, router } from '@inertiajs/vue3';
    import draggable from 'vuedraggable';

    const props = defineProps({
        projects: Object,
    })

const updateProjectOrder = () => {
    props.projects.map((project, index) => project.project_order = index + 1)
    router.post(route('projects.sort'), {
        'projects': props.projects
    })
}

</script>

<template>
    <Head title="Projects Index" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Projects</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex justify-end m-2 p-2">
                    <Link :href="route('projects.create')" class="px-4 py-2 bg-indigo-500 hover:bg-indigo-700 hover:dark:bg-indigo-700 text-white rounded-md">New Project</Link>
                </div>
                <div class="flex flex-col overflow-x-auto">
                    <div class="flex text-xs text-start text-gray-200 uppercase bg-gray-500 dark:bg-gray-700 dark:text-gray-400">
                        <div scope="col" class="w-10 py-3"></div>
                        <div scope="col" class="w-20 py-3 text-center">ID</div>
                        <div scope="col" class="w-48 py-3">Name</div>
                        <div scope="col" class="w-72 py-3">Description</div>
                        <div scope="col" class="w-28 py-3">Skills</div>
                        <div scope="col" class="w-28 py-3">Image</div>
                        <div scope="col" class="w-28 py-3 text-center">Order</div>
                    </div>

                    <draggable :list="projects" item-key="index" handle=".handle" @start="drag-true" @end="drag-false" @change="updateProjectOrder()">
                        <template #item="{element}">
                            <div class="flex items-center justify-start">
                                <div class="w-10 py-3">
                                    <!-- <Bars2Icon class="w-10 h-10 cursor-grab handle text-start bg-light-tertiary dark:bg-dark-secondary text-black dark:text-dark-primary p-1 rounded-md" /> -->
                                </div>
                                <div scope="row" class="w-20 py-4 text-center">{{ element.id }}</div>
                                <div class="w-48 py-4">{{ element.name }}</div>
                                <div class="w-72 py-4">{{ element.description }}</div>
                                <div class="w-28 py-4"><span v-for="skill in element.skills" :key="skill.id">{{skill.name}}<br /></span></div>
                                <div class="w-28 py-4"><img :src="element.image" alt="" class="w-12 h-12 rounded-full"></div>
                                <div class="w-28 py-4 text-center">{{ element.project_order }}</div>
                                <div class="w-28 py-4">
                                    <Link :href="route('projects.edit', element.id)" class="font-medium text-blue-500 hover:text-blue-700 mr-2">Edit</Link>
                                    <Link :href="route('projects.destroy', element.id)" method="delete" as="button" type="button" class="font-medium text-red-500 hover:text-red-700 mr-2">Delete</Link>
                                </div>
                            </div>
                        </template>
                    </draggable>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
