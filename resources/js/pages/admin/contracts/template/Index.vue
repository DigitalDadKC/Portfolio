<script setup lang="ts">
import { computed, ref } from 'vue'
import CategoryPanel from './Components/CategoryPanel.vue'
import ClausePanel from './Components/ClausePanel.vue'

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

const categories = ref<Category[]>([
    { id: 1, name: 'Payment Terms', slug: 'payment-terms', clauses_count: 3 },
    { id: 2, name: 'Confidentiality', slug: 'confidentiality', clauses_count: 2 },
    { id: 3, name: 'Termination', slug: 'termination', clauses_count: 2 },
    { id: 4, name: 'Indemnification', slug: 'indemnification', clauses_count: 1 },
])

const clauses = ref<Clause[]>([
    {
        id: 1,
        category_id: 1,
        name: 'Standard Payment Terms',
        content: 'Payment shall be made within thirty (30) days...',
        is_active: true,
    },
    {
        id: 2,
        category_id: 1,
        name: 'Late Payment Penalties',
        content: 'Late payments shall be subject to...',
        is_active: true,
    },
    {
        id: 3,
        category_id: 1,
        name: 'Payment Schedule',
        content: 'Payments shall be made according to...',
        is_active: true,
    },
    {
        id: 4,
        category_id: 2,
        name: 'Confidential Information',
        content: 'Each party agrees to maintain...',
        is_active: true,
    },
    {
        id: 5,
        category_id: 2,
        name: 'Disclosure Restrictions',
        content: 'Neither party shall disclose...',
        is_active: true,
    },
])

const selectedCategoryId = ref<number | null>(1)

const selectedCategory = computed(() =>
    categories.value.find(
        category => category.id === selectedCategoryId.value
    ) ?? null
)

const selectedClauses = computed(() =>
    clauses.value.filter(
        clause => clause.category_id === selectedCategoryId.value
    )
)

function selectCategory(id: number) {
    selectedCategoryId.value = id
}

function createCategory(category: Omit<Category, 'id' | 'clauses_count'>) {
    const id = Math.max(0, ...categories.value.map(c => c.id)) + 1

    categories.value.push({
        id,
        ...category,
        clauses_count: 0,
    })

    selectedCategoryId.value = id
}

function updateCategory(updated: Category) {
    const index = categories.value.findIndex(c => c.id === updated.id)

    if (index !== -1) {
        categories.value[index] = updated
    }
}

function deleteCategory(id: number) {
    categories.value = categories.value.filter(c => c.id !== id)
    clauses.value = clauses.value.filter(c => c.category_id !== id)

    if (selectedCategoryId.value === id) {
        selectedCategoryId.value = categories.value[0]?.id ?? null
    }
}

function createClause(clause: Omit<Clause, 'id'>) {
    const id = Math.max(0, ...clauses.value.map(c => c.id)) + 1

    clauses.value.push({ id, ...clause })

    const category = categories.value.find(
        c => c.id === clause.category_id
    )

    if (category) category.clauses_count++
}

function updateClause(updated: Clause) {
    const index = clauses.value.findIndex(c => c.id === updated.id)

    if (index !== -1) {
        clauses.value[index] = updated
    }
}

function deleteClause(id: number) {
    const clause = clauses.value.find(c => c.id === id)

    clauses.value = clauses.value.filter(c => c.id !== id)

    if (clause) {
        const category = categories.value.find(
            c => c.id === clause.category_id
        )

        if (category) category.clauses_count--
    }
}
</script>

<template>
    <div class="space-y-6 p-6">

        <div>
            <h1 class="text-2xl font-semibold tracking-tight">
                Clause Library
            </h1>

            <p class="text-sm text-muted-foreground">
                Manage reusable contractual clauses and categories.
            </p>
        </div>

        <!-- Two Panel Layout -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-[280px_1fr]">

            <CategoryPanel
                :categories="categories"
                :selected-category-id="selectedCategoryId"
                @select="selectCategory"
                @create="createCategory"
                @update="updateCategory"
                @delete="deleteCategory"
            />

            <ClausePanel
                :category="selectedCategory"
                :clauses="selectedClauses"
                @create="createClause"
                @update="updateClause"
                @delete="deleteClause"
            />

        </div>
    </div>
</template>