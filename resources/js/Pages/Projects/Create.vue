<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head, useForm, router } from '@inertiajs/vue3';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import InputError from '@/Components/InputError.vue';
    import InputLabel from '@/Components/InputLabel.vue';
    import TextInput from '@/Components/TextInput.vue';

    const props = defineProps({
        skills: Array,
        errors: Object
    })

    const form = useForm({
        name: '',
        image: null,
        description: null,
        skills_array: props.skills,
        selected_skills: null,
        project_url: ''
    });

const submit = () => {
    router.post(route('projects.store'), {
        name: form.name,
        description: form.description,
        image: form.image,
        skills: form.selected_skills?.map((skill) => skill.id),
        project_url: form.project_url
    });
};
</script>

<template>
    <Head title="New Project" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">New Project</h2>
        </template>

        <div class="py-12">
            <div class="max-w-md mx-auto sm:px-6 lg:px-8">
        <form @submit.prevent="submit" class="p-4">
            <div>
                <InputLabel for="skills" class="typo__label">Select Skill</InputLabel>
                        <div>
                            <!-- <VueMultiselect
                            v-model="form.selected_skills"
                            :options="form.skills_array"
                            :multiple="true"
                            :close-on-select="false"
                            placeholder="Pick a skill"
                            label="name"
                            track-by="id"
                            :allow-empty="false"
                        /> -->
                        </div>
                        <InputError class="mt-2" :message="props.errors.skills" />
            </div>
            <div>
                <InputLabel for="name" value="Name" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    autofocus
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="props.errors.name" />
            </div>
            <div>
                <InputLabel for="description" value="Description" />

                <TextInput
                    id="description"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.description"
                    autofocus
                    autocomplete="description"
                />

                <InputError class="mt-2" :message="$page.props.errors.description" />
            </div>
            <div>
                <InputLabel for="project_url" value="URL" />

                <TextInput
                    id="project_url"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.project_url"
                    autocomplete="projecturl"
                />

                <InputError class="mt-2" :message="props.errors.project_url" />

            </div>
            <div class="mt-2">
                <InputLabel for="image" value="Image" />

                <TextInput
                    id="image"
                    type="file"
                    class="mt-1 block w-full"
                    @input="form.image = $event.target.files[0]"
                />

                <InputError class="mt-2" :message="props.errors.image" />
            </div>

            <div class="flex items-center justify-end mt-4">

                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Store
                </PrimaryButton>
            </div>
        </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
