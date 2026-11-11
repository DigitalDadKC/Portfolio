<script setup lang="ts">
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Textarea } from '@/components/ui/textarea'
import { Pencil, Plus } from 'lucide-vue-next'
import { Dialog, DialogTrigger, DialogContent, DialogHeader, DialogTitle, DialogFooter } from '@/components/ui/dialog'

const props = defineProps<{
    clause: any
    categoryId: number | null
}>()

const name = ref(props.clause?.name ?? '')
const content = ref(props.clause?.content ?? '')
const isDialogOpen = ref(false)

function submit() {
    if (!props.clause) {
        router.post(route('admin.contract-template.store'), {
            name: name.value,
            content: content.value,
            category: props.categoryId,
        }, {
            onSuccess: () => {
                isDialogOpen.value = false
                name.value = ''
                content.value = ''
            }
        })
    } else {
        router.patch(route('admin.contract-template.update', props.clause.id), {
            name: name.value,
            content: content.value,
            category: props.categoryId,
        },{
            onSuccess: () => {
                isDialogOpen.value = false
            }
        })
    }
}
</script>

<template>
    <Dialog v-model:open="isDialogOpen">
        <DialogTrigger as-child>
            <Button v-if="!props.clause">
                <Plus class="h-4 w-4" />
            </Button>

            <Button
                v-else
                variant="ghost"
                size="icon"
                class="h-8 w-8 text-muted-foreground hover:text-primary"
            >
                <Pencil class="h-4 w-4" />
                <span class="sr-only">Edit Clause</span>
            </Button>
        </DialogTrigger>

        <DialogContent class="max-w-2xl" aria-describedby="Clause Panel">
            <DialogHeader>
                <DialogTitle>
                    {{ clause ? 'Edit Clause' : 'Create Default Clause' }}
                </DialogTitle>
            </DialogHeader>

            <div class="space-y-4 py-4">

                <div class="space-y-2">
                    <label class="text-sm font-medium">Clause Name</label>
                    <Input v-model="name" placeholder="Standard Payment Terms" />
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-medium">Clause Content</label>

                    <Textarea
                        v-model="content"
                        placeholder="Enter contractual language..."
                        class="min-h-[250px]"
                    />
                </div>
            </div>

            <DialogFooter>
                <Button variant="outline" @click="isDialogOpen = false">
                    Cancel
                </Button>

                <Button :disabled="!name.trim() || !content.trim()" @click="submit">
                    {{ clause ? 'Update Clause' : 'Save Clause' }}
                </Button>
            </DialogFooter>

        </DialogContent>
    </Dialog>
</template>