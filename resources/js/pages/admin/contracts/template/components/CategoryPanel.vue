<script setup lang="ts">
import { ref, watch, computed, useTemplateRef, shallowRef, nextTick } from 'vue'
import type { ComponentPublicInstance } from 'vue'
import { router } from '@inertiajs/vue3'
import { Input } from '@/components/ui/input'
import { ScrollArea } from '@/components/ui/scroll-area'
import { Separator } from '@/components/ui/separator'
import { Grip, Folder } from 'lucide-vue-next'
import CategoryDialog from '../modals/CategoryDialog.vue'
import CategoryDeleteDialog from '../modals/CategoryDeleteDialog.vue'
import { useSortable } from '@vueuse/integrations/useSortable'

interface Category {
    id: number
    name: string
    slug: string
    clauses_count: number
}

const props = defineProps<{
    categories: Category[]
    selectedCategoryId: number | null
}>()

const emit = defineEmits(['select'])
const search = ref('')
const isSearching = computed(() => search.value.trim() !== '')

const el = useTemplateRef<HTMLElement | ComponentPublicInstance>('el')
const list = shallowRef<Category[]>([...props.categories])

const filteredList = computed(() =>
    list.value.filter(c => c.name.toLowerCase().includes(search.value.toLowerCase()))
)

const { option } = useSortable(el, list, {
    handle: '.handle',
    animation: 200,

    onEnd: async (event) => {
        if (event.oldIndex === event.newIndex) return

        await nextTick()
        saveOrder()
    },
})

watch(isSearching, (searching) => option('disabled', searching))

const saveOrder = () => {
    router.post(route('admin.clause-categories.sort'), {
        ids: list.value.map(category => category.id),
    }, {
        preserveScroll: true,
        preserveState: true,
        only: ['categories'],
        onError: () => {
            list.value = [...props.categories]
        },
    })
}

watch(() => props.categories, (categories) => {
    list.value = [...categories]
}, {
    deep: true,
})

</script>

<template>
    <div class="rounded-lg border bg-card">

        <div class="flex items-center justify-between p-4">
            <div>
                <h2 class="font-semibold">Categories</h2>
                <p class="text-xs text-muted-foreground">
                    {{ list.length }} categories
                </p>
            </div>

            <CategoryDialog :category="null" />
        </div>

        <Separator />

        <div class="p-3">
            <Input
                v-model="search"
                placeholder="Search categories..."
            />
        </div>

        <ScrollArea class="h-[450px]">
            <div class="space-y-1 p-2" ref="el">

                <button
                    v-for="category in filteredList"
                    :key="category.id"
                    class="group flex w-full items-center gap-3 rounded-md px-3 py-3 text-left transition-colors"
                    :class="selectedCategoryId === category.id
                        ? 'bg-accent text-accent-foreground'
                        : 'hover:bg-muted'"
                        @click="emit('select', category.id)"
                >
                    <div
                        class="handle flex items-center justify-center transition-colors duration-200"
                        :class="isSearching
                            ? 'cursor-not-allowed text-gray-200 dark:text-gray-700'
                            : 'cursor-grab active:cursor-grabbing text-gray-300 hover:text-indigo-500 dark:text-gray-600 dark:hover:text-indigo-400'"
                        :title="isSearching ? 'Clear search to reorder' : 'Drag to reorder'"
                    >
                        <Grip class="w-5 h-5 transition-transform duration-200 hover:scale-110" />
                    </div>
                    <Folder class="size-8 shrink-0 text-muted-foreground" />

                    <span class="flex-1 truncate text-sm font-medium">
                        {{ category.name }}
                    </span>

                    <span class="text-xs text-muted-foreground">
                        {{ category.clauses_count }}
                    </span>

                    <span class="hidden gap-1 group-hover:flex">
                        <CategoryDialog :category />
                        <CategoryDeleteDialog :category />
                    </span>
                </button>

            </div>
        </ScrollArea>
    </div>
</template>