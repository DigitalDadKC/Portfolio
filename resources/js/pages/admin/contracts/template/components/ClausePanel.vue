<script setup lang="ts">
import { computed, ref } from 'vue'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Badge } from '@/components/ui/badge'
import { Separator } from '@/components/ui/separator'
import { Plus, Search, FileText, Pencil, Trash2 } from 'lucide-vue-next'
import ClauseDialog from '../modals/ClauseDialog.vue'
import ClauseDeleteDialog from '../modals/ClauseDeleteDialog.vue'

interface Category {
    id: number
    name: string
}

interface Clause {
    id: number
    category_id: number
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

const filteredClauses = computed(() =>
    props.clauses.filter(clause =>
        clause.name.toLowerCase().includes(search.value.toLowerCase())
    )
)

</script>

<template>
    <div class="rounded-lg border bg-card">

        <div class="flex flex-col gap-4 p-4 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <h2 class="text-lg font-semibold">
                    {{ category?.name ?? 'Default Clauses' }}
                </h2>

                <p class="text-sm text-muted-foreground">
                    {{ clauses.length }} clauses
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

        <div v-if="!category" class="p-12 text-center">
            <FileText class="mx-auto mb-3 size-10 text-muted-foreground" />

            <h3 class="font-medium">Select a category</h3>

            <p class="mt-1 text-sm text-muted-foreground">
                Choose a category to view its clauses.
            </p>
        </div>

        <div v-else-if="filteredClauses.length" class="divide-y">

            <div
                v-for="clause in filteredClauses"
                :key="clause.id"
                class="flex items-center justify-between gap-4 p-4 hover:bg-muted/50"
            >
                <div class="flex min-w-0 items-center gap-3">

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

        <div v-else class="p-12 text-center">
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