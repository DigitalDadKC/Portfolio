<script setup>
import { ref, watch } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import EstimatingLayout from '@/Layouts/EstimatingLayout.vue';

const props = defineProps({
    new: Boolean,
    company: Object,
    states: Object,
})

const dialog = ref(false)
const form = useForm({
    id: props.company.id,
    name: props.company.name,
    address: props.company.address,
    city: props.company.city,
    state: props.company.state,
    logo: props.company.logo,
    image: props.company.logo
})

const submit = () => {
    console.log(props.new)
    if(props.new) {
        form.post(route('company.store'), {
            onSuccess: () => {
                dialog.value = false
                form.reset()
            }
        })
    } else {
        form.post(route('company.update', form.id), {
            _method: 'put',
            onSuccess: () => {
                dialog.value = false
            }
        })
    }
}

const changePhoto = (e) => {
    const file = e.target.files[0];
    if(!file) {return}
    form.logo = file
    form.image = URL.createObjectURL(file)
  }

</script>

<template>

<EstimatingLayout>
    <Head title="Company" />

    <v-container class="w-100 w-xl-75 bg-grey-lighten-2 rounded-lg">
        <v-card>
            <v-text-field v-model="form.name" hide-details="auto" density="compact" label="Company Name"></v-text-field>
            <v-text-field v-model="form.address" hide-details="auto" density="compact" label="Address *" class="bg-grey-lighten-4 my-1"></v-text-field>
            <div v-if="form.errors['job.address']" class="text-red-400">{{ form.errors['job.address'] }}</div>
            <v-text-field v-model="form.city" hide-details="auto" density="compact" label="City *" class="bg-grey-lighten-4 my-1"></v-text-field>
            <div v-if="form.errors['job.city']" class="text-red-400">{{ form.errors['job.address'] }}</div>
            <v-select v-model="form.state" hide-details="auto" :items="props.states" item-title="state" item-value="id" label="State *" density="compact" class="bg-grey-lighten-4 my-1"></v-select>
            <div v-if="form.errors['job.state.id']" class="text-red-400">{{ form.errors['job.address'] }}</div>
            <v-file-input v-model="form.logo" show-size type="file" accept="image/*" label="Company Logo" @input="changePhoto($event)"></v-file-input>
            <v-img height="255" aspect-ratio="16/9" :src="form.image"></v-img>
        </v-card>

        <v-card-actions>
            <v-btn color="primary" type="submit" text="Update" class="float-right" @click="submit()"></v-btn>
        </v-card-actions>
    </v-container>

</EstimatingLayout>
</template>
