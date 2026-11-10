<script setup lang="ts">
import { ref, watch } from 'vue'

import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Textarea } from '@/components/ui/textarea'
import { Checkbox } from '@/components/ui/checkbox'

import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
    DialogFooter,
} from '@/components/ui/dialog'

const props = defineProps<{
    open: boolean
    clause: any
    categoryId: number | null
}>()

const emit = defineEmits(['update:open', 'save'])

const name = ref('')
const content = ref('')
const isActive = ref(true)

watch(() => props.open, open => {
    if (open) {
        name.value = props.clause?.name ?? ''
        content.value = props.clause?.content ?? ''
        isActive.value = props.clause?.is_active ?? true
    }
})

function save() {
    emit('save', {
        ...props.clause,
        category_id: props.categoryId,
        name: name.value,
        content: content.value,
        is_active: isActive.value,
    })
}
</script>

<template>
    <Dialog :open="open" @update:open="emit('update:open', $event)">
        <DialogContent class="max-w-2xl">

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

                <div class="flex items-center gap-2">
                    <Checkbox
                        id="is-active"
                        :checked="isActive"
                        @update:checked="isActive = $event"
                    />

                    <label for="is-active" class="text-sm">
                        Active clause
                    </label>
                </div>

            </div>

            <DialogFooter>
                <Button variant="outline" @click="emit('update:open', false)">
                    Cancel
                </Button>

                <Button
                    :disabled="!name.trim() || !content.trim()"
                    @click="save"
                >
                    Save Clause
                </Button>
            </DialogFooter>

        </DialogContent>
    </Dialog>
</template>