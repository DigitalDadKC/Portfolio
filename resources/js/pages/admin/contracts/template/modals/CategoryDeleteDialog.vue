
<script setup lang="ts">
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import { Button } from '@/components/ui/button'
import {
    Dialog,
    DialogTrigger,
    DialogContent,
    DialogHeader,
    DialogTitle,
    DialogDescription,
    DialogFooter,
} from '@/components/ui/dialog'
import { Trash2 } from 'lucide-vue-next'

const props = defineProps<{
    category: {
        id: number
        name: string
        clauses_count: number
    }
}>()

const isDialogOpen = ref(false)
const isDeleting = ref(false)

const submit = () => {
    isDeleting.value = true

    router.delete(route('admin.clause-categories.destroy', props.category.id), {
        onSuccess: () => {
            isDialogOpen.value = false
        },
        onFinish: () => {
            isDeleting.value = false
        },
    })
}
</script>

<template>
    <Dialog v-model:open="isDialogOpen">
        <DialogTrigger as-child>
            <Button
                variant="ghost"
                size="icon"
                class="h-8 w-8 text-muted-foreground hover:text-destructive"
            >
                <Trash2 class="h-4 w-4" />
                <span class="sr-only">Delete Category</span>
            </Button>
        </DialogTrigger>

        <DialogContent>
            <DialogHeader>
                <DialogTitle>Delete Category?</DialogTitle>

                <DialogDescription>
                    Are you sure you want to delete
                    <strong>{{ category.name }}</strong>?
                    This action cannot be undone.
                </DialogDescription>
            </DialogHeader>

            <div class="rounded-md bg-muted p-3 text-sm">
                <div class="font-medium">{{ category.name }}</div>
                <div class="text-muted-foreground">
                    {{ category.clauses_count }} associated clauses
                </div>
            </div>

            <DialogFooter>
                <Button
                    variant="outline"
                    :disabled="isDeleting"
                    @click="isDialogOpen = false"
                >
                    Cancel
                </Button>

                <Button
                    variant="destructive"
                    :disabled="isDeleting"
                    @click="submit"
                >
                    {{ isDeleting ? 'Deleting...' : 'Delete Category' }}
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
