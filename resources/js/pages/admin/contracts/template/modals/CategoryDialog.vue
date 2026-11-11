<script setup lang="ts">
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Plus, Pencil } from 'lucide-vue-next'
import { Dialog, DialogTrigger, DialogContent, DialogHeader, DialogTitle, DialogFooter } from '@/components/ui/dialog'

const props = defineProps<{
    category: {
        id: number
        name: string
        clauses_count: number
    } | null
}>()

const isDialogOpen = ref(false)
const name = ref(props.category?.name ?? '')
const isSubmitting = ref(false)

const submit = () => {
    isSubmitting.value = true
    if (!props.category) {
        router.post(route('admin.clause-categories.store'), {
            name: name.value
        }, {
            onSuccess: () => {
                isDialogOpen.value = false
                name.value = ''
            },
            onFinish: () => {
                isSubmitting.value = false
            },
        })
    } else {
        router.patch(route('admin.clause-categories.update', props.category.id), {
            name: name.value
        },{
            onSuccess: () => {
                isDialogOpen.value = false
            },
            onFinish: () => {
                isSubmitting.value = false
            },
        })
    }
}
</script>

<template>
    <Dialog v-model:open="isDialogOpen">
        <DialogTrigger as-child>
            <Button v-if="!props.category" class="gap-2">
                <Plus class="h-4 w-4" />
            </Button>

            <Button
                v-else
                variant="ghost"
                size="icon"
                class="h-8 w-8 text-muted-foreground hover:text-primary"
            >
                <Pencil class="h-4 w-4" />
                <span class="sr-only">Edit Category</span>
            </Button>
        </DialogTrigger>

        <DialogContent aria-describedby="Category Panel">
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
            </div>

            <DialogFooter>
                <Button variant="outline" @click="isDialogOpen = false">
                    Cancel
                </Button>

                <Button :disabled="isSubmitting || !name.trim()" @click="submit">
                    {{ isSubmitting
                        ? 'Saving...'
                        : category
                            ? 'Update Category'
                            : 'Save Category'
                    }}
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>