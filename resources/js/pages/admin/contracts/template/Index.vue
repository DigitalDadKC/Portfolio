<script setup lang="ts">
import { computed, ref } from 'vue'
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3';
import CategoryPanel from './components/CategoryPanel.vue'
import ClausePanel from './components/ClausePanel.vue'

interface Category {
    id: number
    name: string
    slug: string
    clauses_count: number
}

interface Clause {
    id: number
    category_id: number
    name: string
    content: string
    is_active: boolean
}

const props = defineProps<{
    categories: Category[]
    clauses: Clause[]
}>()

const selectedCategoryId = ref<number | null>(1)
// const selectedCategoryIndex = ref<number | null>(0)

const selectedCategory = computed(() =>
    props.categories.find(
        category => category.id === selectedCategoryId.value
    ) ?? null
)

const selectedCategoryIndex = computed(() => {
    return props.categories.map(c => c.id).indexOf(selectedCategoryId.value)+1
})

const selectedClauses = computed(() =>
    props.clauses.filter(
        clause => clause.clause_category_id === selectedCategoryId.value
    )
)

function selectCategory(id: number) {
    selectedCategoryId.value = id
}
</script>

<template>
    <Head title="Contract Template" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center">Contract Template</h2>
        </template>

        <div class="container mx-auto w-full">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight">
                    Clause Library
                </h1>

                <p class="text-sm text-muted-foreground">
                    Manage reusable contractual clauses and categories.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-[280px_1fr]">

                <CategoryPanel
                    :categories
                    :selected-category-id="selectedCategoryId"
                    @select="selectCategory"
                />

                <ClausePanel
                    :category="selectedCategory"
                    :clauses="selectedClauses"
                    :selected-category-index="selectedCategoryIndex"
                />

            </div>
        </div>
    </AuthenticatedLayout>
</template>
