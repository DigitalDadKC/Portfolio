<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { Notebook, ArrowLeft, Trash2 } from 'lucide-vue-next';
import ProjectLayout from './ProjectLayout.vue';

const props = defineProps({
    proposalId: Number,
})

const links = [
    { name: "Estimating", url: '/estimating/jobs', icon: Notebook },
    { name: "Customers", url: '/customers', icon: Notebook },
    { name: "Admin", url: '/admin', icon: Notebook },
]

function deleteProposal() {
    if (!confirm('Are you sure you want to delete this proposal? This action cannot be undone.')) {
        return;
    }

    router.delete(route('proposals.destroy', props.proposalId));
}

</script>

<template>
    <Head title="Estimating" />

    <ProjectLayout title="Construction Estimating Software" :show-sidebar="true">
        <template #navigation>
            <ul class="font-medium flex flex-col p-4 md:p-0 mt-4 rounded-lg md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 dark:border-dark-tertiary">
                <li v-for="(link, index) in links" :key="index" class="py-2">
                    <Link :href="link.url"
                        class="text-light-quatrenary dark:text-dark-quatrenary hover:text-light-quatrenary hover:border-b-2 border-light-quatrenary dark:hover:text-dark-tertiary"
                        :aria-label="link.name" aria-current="page">{{ link.name }}</Link>
                </li>
            </ul>
        </template>

        <slot />

        <div
            class="fixed bottom-0 left-0 right-0 z-50 border-t
                   border-gray-200 bg-white/95 backdrop-blur
                   dark:border-gray-700 dark:bg-gray-900/95"
        >
            <div
                class="mx-auto flex max-w-screen-2xl items-center
                       justify-between px-4 py-3 sm:px-6 lg:px-8"
            >
                <Link
                    :href="route('jobs.index')"
                    class="inline-flex items-center gap-2 rounded-lg
                           px-4 py-2 text-sm font-medium
                           text-gray-700 transition
                           hover:bg-gray-100
                           dark:text-gray-200
                           dark:hover:bg-gray-800"
                >
                    <ArrowLeft class="h-5 w-5" />
                    <span>Back to Jobs Index</span>
                </Link>
                
            <button
                type="button"
                @click="deleteProposal"
                class="inline-flex items-center gap-2 rounded-lg bg-red-600
                    px-4 py-2 text-sm font-medium text-white
                    transition hover:bg-red-700 focus:outline-none
                    focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
            >
                <Trash2 class="h-5 w-5" />
                <span>Delete Proposal</span>
            </button>

            </div>
        </div>

    </ProjectLayout>
</template>
