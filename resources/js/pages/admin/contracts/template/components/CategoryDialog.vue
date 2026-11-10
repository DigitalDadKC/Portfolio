<script setup lang="ts">
import { ref, watch } from 'vue'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
    DialogFooter,
} from '@/components/ui/dialog'

const props = defineProps<{
    open: boolean
    category: {
        id: number
        name: string
        slug: string
        clauses_count: number
    } | null
}>()

const emit = defineEmits(['update:open', 'save'])

const name = ref('')
const slug = ref('')

watch(
    () => props.open,
    open => {
        if (open) {
            name.value = props.category?.name ?? ''
            slug.value = props.category?.slug ?? ''
        }
    }
)

function save() {
    emit('save', {
        ...props.category,
        name: name.value,
        slug: slug.value,
    })
}
</script>

<template>
    <Dialog :open="open" @update:open="emit('update:open', $event)">
        <DialogContent>
            <DialogHeader>
                <DialogTitle>
                    {{ category ? 'Edit Category' : 'Create Category' }}
                </DialogTitle>
            </DialogHeader>

            <div class="space-y-4 py-4">
                <div class="space-y-2">
                    <label class="text-sm font-medium">Category Name</label>
                    <Input v-model="name" placeholder="Payment Terms" />
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-medium">Slug</label>
                    <Input v-model="slug" placeholder="payment-terms" />
                </div>
            </div>

            <DialogFooter>
                <Button variant="outline" @click="emit('update:open', false)">
                    Cancel
                </Button>

                <Button :disabled="!name.trim()" @click="save">
                    Save Category
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>