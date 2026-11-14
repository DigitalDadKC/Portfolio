
<script setup lang="ts">
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import GuestLayout from '@/layouts/GuestLayout.vue'
import ProjectHeader from '@/components/ProjectHeader.vue'
import { Landmark, Pencil, Trash2, X } from '@lucide/vue'
import { Input } from '@/components/ui/input'
import { Button } from '@/components/ui/button'
import DatePicker from '@/components/DatePicker.vue'
import { useDateFormat } from '@vueuse/core'

interface Favor {
    id: number
    favor: string
    date: string | null
}

const props = defineProps<{
    favors: Favor[]
}>()

const favor = ref('')
const date = ref('')
const editingFavor = ref<number | null>(null)

const submit = () => {
    const data = {
        favor: favor.value,
        date: date.value || null,
    }

    if (editingFavor.value) {
        router.patch(route('husband.update', editingFavor.value), data, {
            preserveScroll: true,
            onSuccess: () => resetForm(),
        })
    } else {
        router.post(route('husband.store'), data, {
            preserveScroll: true,
            onSuccess: () => resetForm(),
        })
    }
}

const editFavor = (item: Favor) => {
    editingFavor.value = item.id
    favor.value = item.favor
    date.value = item.date ?? ''
}

const resetForm = () => {
    favor.value = ''
    date.value = ''
    editingFavor.value = null
}

const deleteFavor = (id: number) => {
    if (!confirm('Are you sure you want to delete this favor?')) return

    router.delete(route('husband.destroy', id), {
        preserveScroll: true,
        onSuccess: () => {
            if (editingFavor.value === id) {
                resetForm()
            }
        },
    })
}
</script>

<template>
    <GuestLayout title="Husband Duties">
        <main class="mx-auto max-w-[1600px] space-y-8 px-4 py-8 sm:px-6 lg:px-10 lg:py-10">
            <div class="mx-auto max-w-7xl space-y-8">

                <ProjectHeader
                    title="Husband Favors"
                    subtitle="husband duties"
                    :total="props.favors.length"
                    total_category="favors"
                >
                    Log of nice things I have done for my wife lately

                    <template #icon>
                        <Landmark class="size-5" />
                    </template>
                </ProjectHeader>

                <div class="flex flex-wrap gap-2">
                    <Input
                        v-model="favor"
                        placeholder="Enter favor"
                        class="flex-1"
                    />

                    <DatePicker v-model="date" />

                    <Button @click="submit">
                        {{ editingFavor ? 'Update' : 'Save' }}
                    </Button>

                    <Button
                        v-if="editingFavor"
                        variant="outline"
                        @click="resetForm"
                    >
                        <X class="size-4" />
                        Cancel
                    </Button>
                </div>

                <div class="space-y-2">
                    <div
                        v-for="item in props.favors"
                        :key="item.id"
                        class="flex items-center justify-between gap-4 rounded-lg border p-4"
                        :class="editingFavor === item.id ? 'border-primary bg-muted' : ''"
                    >
                        <div class="min-w-0 flex-1">
                            <div class="font-medium">
                                {{ item.favor }}
                            </div>

                            <div class="text-sm text-muted-foreground">
                                {{
                                    item.date
                                        ? useDateFormat(item.date, 'dddd, MMMM D, YYYY').value
                                        : 'No date'
                                }}
                            </div>
                        </div>

                        <div class="flex shrink-0 gap-2">
                            <Button
                                variant="outline"
                                size="icon"
                                @click="editFavor(item)"
                                title="Edit favor"
                            >
                                <Pencil class="size-4" />
                            </Button>

                            <Button
                                variant="destructive"
                                size="icon"
                                @click="deleteFavor(item.id)"
                                title="Delete favor"
                            >
                                <Trash2 class="size-4" />
                            </Button>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </GuestLayout>
</template>
