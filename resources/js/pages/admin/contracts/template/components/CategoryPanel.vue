<script setup lang="ts">
import { ref } from 'vue'

import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { ScrollArea } from '@/components/ui/scroll-area'
import { Separator } from '@/components/ui/separator'
import { Folder, Plus, Pencil, Trash2 } from 'lucide-vue-next'

import CategoryDialog from './CategoryDialog.vue'

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

const emit = defineEmits([
    'select',
    'create',
    'update',
    'delete',
])

const search = ref('')
const dialogOpen = ref(false)
const editingCategory = ref<Category | null>(null)

function openCreate() {
    editingCategory.value = null
    dialogOpen.value = true
}

function openEdit(category: Category) {
    editingCategory.value = category
    dialogOpen.value = true
}

function saveCategory(category: Category) {
    if (editingCategory.value) {
        emit('update', category)
    } else {
        emit('create', category)
    }

    dialogOpen.value = false
}
</script>

<template>
    <div class="rounded-lg border bg-card">

        <div class="flex items-center justify-between p-4">
            <div>
                <h2 class="font-semibold">Categories</h2>
                <p class="text-xs text-muted-foreground">
                    {{ categories.length }} categories
                </p>
            </div>

            <Button size="icon" variant="outline" @click="openCreate">
                <Plus class="size-4" />
            </Button>
        </div>

        <Separator />

        <div class="p-3">
            <Input
                v-model="search"
                placeholder="Search categories..."
            />
        </div>

        <ScrollArea class="h-[450px]">
            <div class="space-y-1 p-2">

                <button
                    v-for="category in categories.filter(c =>
                        c.name.toLowerCase().includes(search.toLowerCase())
                    )"
                    :key="category.id"
                    class="group flex w-full items-center gap-3 rounded-md px-3 py-3 text-left transition-colors"
                    :class="selectedCategoryId === category.id
                        ? 'bg-accent text-accent-foreground'
                        : 'hover:bg-muted'"
                    @click="emit('select', category.id)"
                >
                    <Folder class="size-4 shrink-0 text-muted-foreground" />

                    <span class="flex-1 truncate text-sm font-medium">
                        {{ category.name }}
                    </span>

                    <span class="text-xs text-muted-foreground">
                        {{ category.clauses_count }}
                    </span>

                    <span class="hidden gap-1 group-hover:flex">
                        <button
                            type="button"
                            @click.stop="openEdit(category)"
                        >
                            <Pencil class="size-3.5" />
                        </button>

                        <button
                            type="button"
                            @click.stop="emit('delete', category.id)"
                        >
                            <Trash2 class="size-3.5 text-destructive" />
                        </button>
                    </span>
                </button>

            </div>
        </ScrollArea>

        <Separator />

        <div class="p-3">
            <Button
                variant="ghost"
                class="w-full justify-start"
                @click="openCreate"
            >
                <Plus class="mr-2 size-4" />
                Add category
            </Button>
        </div>

        <CategoryDialog
            v-model:open="dialogOpen"
            :category="editingCategory"
            @save="saveCategory"
        />

    </div>
</template>