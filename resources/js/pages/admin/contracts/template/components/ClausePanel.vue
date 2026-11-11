<script setup lang="ts">
import { computed, ref, watch, shallowRef, useTemplateRef, nextTick } from 'vue'
import type { ComponentPublicInstance } from 'vue'
import { router } from '@inertiajs/vue3'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Badge } from '@/components/ui/badge'
import { Separator } from '@/components/ui/separator'
import { Plus, Search, FileText, Pencil, Trash2, Grip } from 'lucide-vue-next'
import ClauseDialog from '../modals/ClauseDialog.vue'
import ClauseDeleteDialog from '../modals/ClauseDeleteDialog.vue'
import { useSortable } from '@vueuse/integrations/useSortable'

interface Category {
    id: number
    name: string
}

interface Clause {
    id: number
    clause_category_id: number
    name: string
    content: string
    is_active: boolean
}

const props = defineProps<{
    category: Category | null
    clauses: Clause[]
}>()

const emit = defineEmits(['create', 'update', 'delete'])

const search = ref('')
const editingClause = ref<Clause | null>(null)
const isSearching = computed(() => search.value.trim() !== '')

const el = useTemplateRef<HTMLElement | ComponentPublicInstance>('el')
const list = shallowRef<Clause[]>([...props.clauses])

const filteredClauses = computed(() =>
    list.value.filter(clause =>
        clause.name.toLowerCase().includes(search.value.toLowerCase())
    )
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
    if (!props.category) return

    router.post(route('admin.contract-template.sort'), {
        category_id: props.category.id,
        ids: list.value.map(clause => clause.id),
    }, {
        preserveScroll: true,
        preserveState: true,
        only: ['clauses'],
        onError: () => {
            list.value = [...props.clauses]
        },
    })
}

watch(() => props.clauses, (clauses) => {
    list.value = [...clauses]
}, {
    deep: true,
})

</script>

<template>
    <div class="rounded-lg border bg-card">

        <div class="flex flex-col gap-4 p-4 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <h2 class="text-lg font-semibold">
                    {{ category?.name ?? 'Default Clauses' }}
                </h2>

                <p class="text-sm text-muted-foreground">
                    {{ list.length }} clauses
                </p>
            </div>

            <ClauseDialog
                :clause="editingClause"
                :category-id="category?.id ?? null"
            />
        </div>

        <Separator />

        <div class="p-4">
            <div class="relative">
                <Search class="absolute left-3 top-2.5 size-4 text-muted-foreground" />

                <Input
                    v-model="search"
                    placeholder="Search clauses..."
                    class="pl-9"
                />
            </div>
        </div>

        <!--
            This container is ALWAYS in the DOM (v-show, not v-if). Sortable binds
            to the element once; if v-if destroyed and recreated it (empty search,
            switching categories) Sortable would be left attached to a dead node.
        -->
        <div v-show="category && filteredClauses.length" ref="el" class="divide-y">

            <!-- Render `list` (via filteredClauses), NOT props.clauses -->
            <div
                v-for="clause in filteredClauses"
                :key="clause.id"
                class="flex items-center justify-between gap-4 p-4 hover:bg-muted/50"
            >
                <div class="flex min-w-0 items-center gap-3">

                    <div
                        class="handle flex items-center justify-center transition-colors duration-200"
                        :class="isSearching
                            ? 'cursor-not-allowed text-gray-200 dark:text-gray-700'
                            : 'cursor-grab active:cursor-grabbing text-gray-300 hover:text-indigo-500 dark:text-gray-600 dark:hover:text-indigo-400'"
                        :title="isSearching ? 'Clear search to reorder' : 'Drag to reorder'"
                    >
                        <Grip class="size-5" />
                    </div>

                    <FileText class="size-5 shrink-0 text-muted-foreground" />

                    <div class="min-w-0">
                        <p class="truncate text-sm font-medium">
                            {{ clause.name }}
                        </p>

                        <p class="truncate text-xs text-muted-foreground">
                            {{ clause.content }}
                        </p>
                    </div>

                </div>

                <div class="flex shrink-0 items-center gap-2">
                    <Badge :variant="clause.is_active ? 'default' : 'secondary'">
                        {{ clause.is_active ? 'Active' : 'Inactive' }}
                    </Badge>

                    <ClauseDialog
                        :clause
                        :category-id="category?.id ?? null"
                    />

                    <ClauseDeleteDialog
                        :clause="clause"
                    />
                </div>
            </div>

        </div>

        <div v-if="!category" class="p-12 text-center">
            <FileText class="mx-auto mb-3 size-10 text-muted-foreground" />

            <h3 class="font-medium">Select a category</h3>

            <p class="mt-1 text-sm text-muted-foreground">
                Choose a category to view its clauses.
            </p>
        </div>

        <div v-else-if="!filteredClauses.length" class="p-12 text-center">
            <FileText class="mx-auto mb-3 size-10 text-muted-foreground" />

            <h3 class="font-medium">No clauses found</h3>

            <p class="mt-1 text-sm text-muted-foreground">
                Add a clause to this category to get started.
            </p>

            <ClauseDialog
                :clause="editingClause"
                :category-id="category?.id ?? null"
            />

        </div>
    </div>
</template>