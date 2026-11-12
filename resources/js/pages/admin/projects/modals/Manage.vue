<script setup lang="ts">
import { ref, computed } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';
import { Switch } from '@/components/ui/switch';
import InputError from '@/components/InputError.vue';
import Skills from '../partials/Skills.vue';
import { Dialog, DialogContent, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { Pencil, Plus } from 'lucide-vue-next';
import Multiselect from '@/components/Multiselect.vue';

const props = defineProps({
    new: Boolean,
    project: Object,
    skills: Array,
})

const isDialogOpen = ref(false)
const form = useForm({
    id: props.project?.id,
    name: props.project?.name,
    description: props.project?.description,
    image: props.project?.image,
    url: props.project?.url,
    active: !!props.project?.active,
    icon: props.project?.icon,
    skills: props.project?.skills,
})

const picture = computed(() => {
    return (form.image?.type?.startsWith('image')) ? URL.createObjectURL(form.image) : form.image
})

const handleImage = (e) => {
    form.image = e.target.files[0]
}

const submit = () => {
    if(props.new) {
        form.post(route('admin.projects.store'), {
            onSuccess: () => {
                form.reset()
                isDialogOpen.value = false
            }
        });
    } else {
        router.post(route('admin.projects.update', props.project?.id), {
            _method: 'put',
            name: form.name,
            description: form.description,
            url: form.url,
            image: form.image,
            active: form.active,
            icon: form.icon,
            skills: form.skills,
        }, {
            preserveScroll: true,
            forceFormData: true,
            onSuccess: () => {
                isDialogOpen.value = false;
            }
        });
    }
};

const domain = window.location.hostname
</script>

<template>
    <Dialog v-model:open="isDialogOpen">
        <DialogTrigger as-child>
            <Button class="cursor-pointer" v-if="props.new">
                <Plus></Plus>
                New Project
            </Button>
            <Button class="cursor-pointer" v-else>
                <Pencil class="cursor-pointer"></Pencil>
            </Button>
        </DialogTrigger>

        <DialogContent class="sm:max-w-[600px] grid-rows-[auto_minmax(0,1fr)_auto] max-h-[90dvh] bg-light-primary dark:bg-dark-primary">
            <DialogHeader class="p-6">
                <DialogTitle>{{(props.new) ? 'New Project' : 'Edit Project' }}</DialogTitle>
            </DialogHeader>

            <div class="flex flex-col gap-4">
                <div>
                    <Label for="name">Name</Label>
                    <Input id="name" class="bg-white dark:bg-dark-tertiary hover:bg-accent hover:dark:bg-input/50" v-model="form.name" />
                </div>
                <div v-if="form.errors.name" class="text-red-500">{{ form.errors.name }}</div>
                <div>
                    <Label for="description">Description</Label>
                    <Input id="description" class="bg-white dark:bg-dark-tertiary hover:bg-accent hover:dark:bg-input/50" v-model="form.description" />
                </div>

                <div>
                    <Label for="url">Project URL</Label>

                    <div class="flex items-center rounded-md border border-input bg-white dark:bg-dark-tertiary">
                        <span class="px-3 text-sm text-muted-foreground whitespace-nowrap">
                            https://{{ domain }}/
                        </span>

                        <Input
                            id="url"
                            v-model="form.url"
                            class="border-0 shadow-none focus-visible:ring-0 focus-visible:ring-offset-0 bg-transparent"
                        />
                    </div>
                </div>


                <!-- <div>
                    <Label for="url">Project URL</Label>
                    <Input id="url" class="bg-white dark:bg-dark-tertiary hover:bg-accent hover:dark:bg-input/50" v-model="form.url" />
                </div> -->
                <div v-if="form.errors.url" class="text-red-500">{{ form.errors.url }}</div>
                <div>
                    <Label for="active">{{ (form.active) ? `Active` : `Inactive` }}</Label>
                    <Switch id="active" v-model="form.active" />
                </div>
                <div>
                    <Label for="icon">Icon</Label>
                    <Input id="icon" class="bg-white dark:bg-dark-tertiary hover:bg-accent hover:dark:bg-input/50" v-model="form.icon" />
                </div>
                <div>
                    <Multiselect v-model="form.skills" :options="props.skills" placeholder="Select skills"/>
                </div>
                <div class="mt-2">
                    <Label for="image">Image</Label>
                    <Input id="image" type="file" class="bg-white dark:bg-dark-tertiary hover:bg-input/50" @change="handleImage($event)" />
                    <InputError class="mt-2" :message="$page.props.errors.image" />
                </div>
                <div class="flex justify-center">
                    <img :src="picture" class="w-60 h-60" />
                </div>
            </div>

            <DialogFooter class="p-6 pt-0">
                <div class="flex gap-2">
                    <Button variant="secondary" class="cursor-pointer" @click="isDialogOpen = false">Cancel</Button>
                    <Button type="submit" class="cursor-pointer" :disabled="form.processing" @click="submit()">Save</Button>
                </div>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
