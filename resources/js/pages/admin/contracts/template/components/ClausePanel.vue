<script setup lang="ts">
import { computed, ref } from 'vue'

import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Badge } from '@/components/ui/badge'
import { Separator } from '@/components/ui/separator'

import { Plus, Search, FileText, Pencil, Trash2 } from 'lucide-vue-next'

import ClauseDialog from './ClauseDialog.vue'

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
const dialogOpen = ref(false)
const editingClause = ref<Clause | null>(null)

const filteredClauses = computed(() =>
    props.clauses.filter(clause =>
        clause.name.toLowerCase().includes(search.value.toLowerCase())
    )
)

function openCreate() {
    editingClause.value = null
    dialogOpen.value = true
}

function openEdit(clause: Clause) {
    editingClause.value = clause
    dialogOpen.value = true
}

function saveClause(clause: Clause) {
    if (editingClause.value) {
        emit('update', clause)
    } else {
        emit('create', clause)
    }

    dialogOpen.value = false
}
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

            <Button
                :disabled="!category"
                @click="openCreate"
            >
                <Plus class="mr-2 size-4" />
                Add Clause
            </Button>

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

                    <Button
                        size="icon"
                        variant="ghost"
                        @click="openEdit(clause)"
                    >
                        <Pencil class="size-4" />
                    </Button>

                    <Button
                        size="icon"
                        variant="ghost"
                        @click="emit('delete', clause.id)"
                    >
                        <Trash2 class="size-4 text-destructive" />
                    </Button>

                </div>
            </div>

        </div>

        <div v-else class="p-12 text-center">
            <FileText class="mx-auto mb-3 size-10 text-muted-foreground" />

            <h3 class="font-medium">No clauses found</h3>

            <p class="mt-1 text-sm text-muted-foreground">
                Add a clause to this category to get started.
            </p>

            <Button class="mt-4" @click="openCreate">
                <Plus class="mr-2 size-4" />
                Add Clause
            </Button>
        </div>

        <ClauseDialog
            v-model:open="dialogOpen"
            :clause="editingClause"
            :category-id="category?.id ?? null"
            @save="saveClause"
        />

    </div>
</template>