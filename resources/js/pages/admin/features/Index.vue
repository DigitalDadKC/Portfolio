<script setup lang="ts">
import { shallowRef, useTemplateRef, nextTick, watch } from 'vue'
import type { ComponentPublicInstance } from 'vue'
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import Manage from './modals/Manage.vue';
import Destroy from './modals/Destroy.vue';
import { Grip } from 'lucide-vue-next';
import { useDateFormat } from '@vueuse/core';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table"
import { useSortable } from '@vueuse/integrations/useSortable'

interface Feature {
    id: number | null
    name: string
    order: number
    created_at: string
    updated_at: string
}

const props = defineProps<{
    features: Feature[]
}>()

const el = useTemplateRef<HTMLElement | ComponentPublicInstance>('el')
const list = shallowRef<Feature[]>([...props.features])

useSortable(el, list, {
    handle: '.handle',
    animation: 200,

    onEnd: async (event) => {
        if (event.oldIndex === event.newIndex) return

        await nextTick()
        updateFeatureOrder()
    },
})

const updateFeatureOrder = () => {
    const previous = list.value

    const ordered = list.value.map((feature, index) => ({ ...feature, order: index }))
    list.value = ordered

    router.post(route('features.sort'), {
        features: ordered.map(({ id, order }) => ({ id, order })),
    }, {
        preserveScroll: true,
        preserveState: true,
        onError: () => {
            list.value = previous
        },
    })
}

watch(() => props.features, (features) => {
    list.value = [...features]
}, {
    deep: true,
})

</script>

<template>
    <Head title="Features" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center">Features</h2>
        </template>

        <div class="container mx-auto w-full mt-20">
            <Table class="bg-light-primary dark:bg-dark-primary w-full">
                <TableHeader class="bg-light-tertiary dark:bg-dark-tertiary">
                    <TableRow>
                        <TableHead class="p-6 text-black"></TableHead>
                        <TableHead class="text-black">Order</TableHead>
                        <TableHead class="p-6 text-black">Feature</TableHead>
                        <TableHead class="text-black">Created</TableHead>
                        <TableHead class="text-black">Updated</TableHead>
                        <TableHead class="text-black text-end">
                            <Manage :new="true"></Manage>
                        </TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody class="bg-white dark:bg-gray-800 dark:border-gray-700" ref="el">
                    <TableRow v-for="feature in list" :key="feature.id" class="w-full">
                        <TableCell class="p-3">
                            <div
                                class="handle flex items-center justify-center cursor-grab active:cursor-grabbing
                                    text-gray-300 hover:text-indigo-500 dark:text-gray-600
                                    dark:hover:text-indigo-400 transition-colors duration-200"
                                title="Drag to reorder"
                            >
                                <Grip class="w-5 h-5 transition-transform duration-200 hover:scale-110" />
                            </div>
                        </TableCell>
                        <TableCell>{{ feature.order }}</TableCell>
                        <TableCell>{{ feature.name }}</TableCell>
                        <TableCell>{{ useDateFormat(feature.created_at, 'MMM d, YYYY') }}</TableCell>
                        <TableCell>{{ useDateFormat(feature.updated_at, 'MMM d, YYYY') }}</TableCell>
                        <TableCell class="flex justify-end gap-4">
                            <Manage :new="false" :feature></Manage>
                            <Destroy :feature></Destroy>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>

    </AuthenticatedLayout>
</template>