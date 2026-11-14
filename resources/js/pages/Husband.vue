<script setup lang="ts">
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import GuestLayout from '@/layouts/GuestLayout.vue';
import ProjectHeader from '@/components/ProjectHeader.vue';
import { Landmark } from '@lucide/vue';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import DatePicker from '@/components/DatePicker.vue';
import { useDateFormat } from '@vueuse/core';

const props = defineProps({
    'favors': Object,
})

const favor = ref('')
const date = ref('')

const submit = () => {
    // form.transform((data) => ({
    //     ...data,
    //     date: (form.date) ? useDateFormat(form.date, 'YYYY-MM-DD').value : null,
    // }))
    // if () {
        router.post(route('husband.store'), {
            favor: favor.value,
            date: date.value
        }), {
            onSuccess: () => {
                // isDialogOpen.value = false
                // form.reset()
            }
        }
    // } else {
    //     router.patch(route('contracts.update'), {
    //         onSuccess: () => {
    //             isDialogOpen.value = false
    //         }
    //     })
    // }
}

</script>

<template>
    <GuestLayout title="Per Diem Explorer" :show-sidebar="true">
        <main class="mx-auto max-w-[1600px] space-y-8 px-4 py-8 sm:px-6 lg:px-10 lg:py-10">
            <div class="mx-auto max-w-7xl space-y-8">
                
                <ProjectHeader
                    title="Husband Favors"
                    subtitle="husband duties"
                    :total="0"
                    total_category="favors"
                >
                    Nice things I have done for my wife lately
                    <template #icon>
                        <Landmark class="size-5" />
                    </template>
                </ProjectHeader>

                <div class="flex">
                    <Input v-model="favor" placeholder="Enter favor" />
                    <DatePicker v-model="date" />
                    <Button @click="submit()">Save</Button>
                </div>

                <div>
                    <div v-for="favor in props.favors" :key="favor.id">
                        <div class="flex justify-between">
                            <div>
                                {{ favor.favor }}
                            </div>
                            <div>
                                {{ useDateFormat(favor.date, 'dddd, MMMM D, YYYY') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </GuestLayout>
</template>