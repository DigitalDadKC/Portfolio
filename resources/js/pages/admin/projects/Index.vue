<script setup lang="ts">
import { shallowRef, useTemplateRef, nextTick, watch, ComponentPublicInstance } from 'vue'
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
import { Head, router, Link } from '@inertiajs/vue3';
import Manage from './modals/Manage.vue';
import Delete from './modals/Delete.vue';
import { Grip, Image as ImageIcon, ExternalLink } from 'lucide-vue-next';
import { useDateFormat } from '@vueuse/core';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table"
import { useSortable } from '@vueuse/integrations/useSortable'

interface Project {
    id: number | null
    name: string
    description: string
    image: string
    url: string
    order: number
    active: boolean
    icon: string
}

interface Skill {
    id: number | null,
    name: string
    image: string
}

const props = defineProps<{
    projects: Project[],
    skills: Skill[],
}>()

const el = useTemplateRef<HTMLElement | ComponentPublicInstance>('el')
const list = shallowRef<Project[]>([...props.projects])

useSortable(el, list, {
    handle: '.handle',
    animation: 150,

    onEnd: async (event) => {
        if (event.oldIndex === event.newIndex) return

        await nextTick()
        updateProjectOrder()
    }
})

const updateProjectOrder = () => {
    const previous = list.value

    const ordered = list.value.map((project, index) => ({...project, order: index}))
    list.value = ordered

    router.post(route('projects.sort'), {
        'projects': ordered.map(({id, order}) => ({id, order})),
    }, {
        preserveScroll: true,
        preserveState: true,
        onError: () => {
            list.value = previous
        }
    })
}

watch(() => (props.projects), (projects) => {
    list.value = projects
}, {
    deep: true,
})


const domain = window.location.origin
console.log(domain)
</script>

<template>
    <Head title="Projects" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center">Projects</h2>
        </template>

        <div class="container mx-auto w-full mt-20">
            <Table class="bg-light-primary dark:bg-dark-primary">
                <TableHeader class="bg-gray-50 dark:bg-gray-900/70">                    <TableRow>
                        <TableHead class="px-4 py-4 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Project</TableHead>
                        <TableHead class="px-4 py-4 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Image</TableHead>
                        <TableHead class="px-4 py-4 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Description</TableHead>
                        <TableHead class="px-4 py-4 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Skills</TableHead>
                        <TableHead class="px-4 py-4 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Image</TableHead>
                        <TableHead class="px-4 py-4 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">URL</TableHead>
                        <TableHead class="px-4 py-4 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Active</TableHead>
                        <TableHead class="px-4 py-4 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Created</TableHead>
                        <TableHead class="px-4 py-4 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Updated</TableHead>
                        <TableHead class="text-black text-center"><Manage :new="true" :skills></Manage></TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody class="bg-white dark:bg-dark-primary" ref="el">
                    <TableRow
                        v-for="project in list"
                        :key="project.id"
                        class="group border-b border-gray-100 dark:border-gray-700/60
                            transition-all duration-200
                            hover:bg-indigo-50/40 dark:hover:bg-indigo-500/5"
                    >
                        <!-- Drag Handle -->
                        <TableCell class="w-12 px-3">
                            <div
                                class="handle flex items-center justify-center cursor-grab active:cursor-grabbing
                                    text-gray-300 hover:text-indigo-500 dark:text-gray-600
                                    dark:hover:text-indigo-400 transition-colors duration-200"
                                title="Drag to reorder"
                            >
                                <Grip class="w-5 h-5 transition-transform duration-200 hover:scale-110" />
                            </div>
                        </TableCell>

                        <!-- Project Name -->
                        <TableCell class="py-4 min-w-[160px]">
                            <div class="flex flex-col gap-1">
                                <span class="font-semibold text-gray-900 dark:text-gray-100
                                            group-hover:text-indigo-600 dark:group-hover:text-indigo-400
                                            transition-colors">
                                    {{ project.name }}
                                </span>
                                <span class="text-xs text-gray-400 dark:text-gray-500">
                                    Project #{{ project.id }}
                                </span>
                            </div>
                        </TableCell>

                        <!-- Description -->
                        <TableCell class="max-w-[240px]">
                            <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-2 leading-relaxed">
                                {{ project.description || 'No description provided' }}
                            </p>
                        </TableCell>

                        <!-- Skills -->
                        <TableCell>
                            <div class="flex flex-wrap gap-1.5 max-w-[220px]">
                                <span
                                    v-for="skill in project.skills"
                                    :key="skill.id"
                                    class="inline-flex items-center rounded-md px-2 py-1
                                        text-xs font-medium
                                        bg-indigo-50 text-indigo-700
                                        dark:bg-indigo-500/10 dark:text-indigo-300
                                        border border-indigo-100 dark:border-indigo-500/20"
                                >
                                    {{ skill.name }}
                                </span>
                                <span
                                    v-if="!project.skills?.length"
                                    class="text-xs text-gray-400 italic"
                                >
                                    No skills
                                </span>
                            </div>
                        </TableCell>

                        <!-- Project Image -->
                        <TableCell>
                            <div
                                class="w-16 h-12 rounded-lg overflow-hidden
                                    bg-gray-100 dark:bg-gray-800
                                    border border-gray-200 dark:border-gray-700
                                    shadow-sm group-hover:shadow-md
                                    transition-shadow duration-200"
                            >
                                <img
                                    v-if="project.image"
                                    :src="project.image"
                                    :alt="project.name"
                                    class="w-full h-full object-cover transition-transform
                                        duration-300 group-hover:scale-110"
                                />
                                <div
                                    v-else
                                    class="w-full h-full flex items-center justify-center
                                        text-gray-400"
                                >
                                    <ImageIcon class="w-5 h-5" />
                                </div>
                            </div>
                        </TableCell>

                        <!-- URL -->
                        <TableCell>                            
                            <Link
                                :href="`${domain}/${project.url.replace(/^\/+/, '')}`"
                                target="_blank"
                                class="
                                    inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5
                                    text-xs font-semibold
                                    text-indigo-600 bg-indigo-50
                                    hover:bg-indigo-100 hover:text-indigo-700
                                    dark:text-indigo-300 dark:bg-indigo-500/10
                                    dark:hover:bg-indigo-500/20
                                    transition-all duration-150"
                                prefetch
                                v-if="project.url"
                            >
                                {{ project.name }}
                                <ExternalLink class="w-3.5 h-3.5" />
                            </Link>
                            <!-- <a
                                v-if="project.url"
                                :href="project.url"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5
                                    text-xs font-semibold
                                    text-indigo-600 bg-indigo-50
                                    hover:bg-indigo-100 hover:text-indigo-700
                                    dark:text-indigo-300 dark:bg-indigo-500/10
                                    dark:hover:bg-indigo-500/20
                                    transition-all duration-150"
                            >
                                Visit
                                <ExternalLink class="w-3.5 h-3.5" />
                            </a> -->
                            <span v-else class="text-xs text-gray-400">—</span>
                        </TableCell>

                        <!-- Active Status -->
                        <TableCell>
                            <span
                                class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1
                                    text-xs font-semibold"
                                :class="project.active
                                    ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400'
                                    : 'bg-gray-100 text-gray-500 dark:bg-gray-700/50 dark:text-gray-400'"
                            >
                                <span
                                    class="w-1.5 h-1.5 rounded-full"
                                    :class="project.active ? 'bg-emerald-500' : 'bg-gray-400'"
                                ></span>
                                {{ project.active ? 'Active' : 'Inactive' }}
                            </span>
                        </TableCell>

                        <!-- Created -->
                        <TableCell>
                            <span class="text-sm text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                {{ useDateFormat(project.created_at, 'MMM d, YYYY') }}
                            </span>
                        </TableCell>

                        <!-- Updated -->
                        <TableCell>
                            <span class="text-sm text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                {{ useDateFormat(project.updated_at, 'MMM d, YYYY') }}
                            </span>
                        </TableCell>

                        <!-- Actions -->
                        <TableCell>
                            <div
                                class="flex items-center justify-center gap-2
                                    opacity-70 group-hover:opacity-100
                                    transition-opacity duration-200"
                            >
                                <Manage :new="false" :project :skills />
                                <Delete :project />
                            </div>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>

    </AuthenticatedLayout>
</template>
