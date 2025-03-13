<script setup>
import { computed, onMounted } from 'vue';
import { Link, Head, useForm } from '@inertiajs/vue3';
import JobLayout from '@/Layouts/EstimatingLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useProjectMath } from '@/Composables/useProjectMath';
import {VDateInput} from 'vuetify/labs/VDateInput';
import draggable from 'vuedraggable';

const props = defineProps({
    new: Boolean,
    job: Object,
    company: Object,
    states: Object,
    unit_of_measurements: Object,
    errors: Object
})

const form = useForm({
    job: props.job,
    PDF: true
})

let  { addLine, addScope, removeLine, removeScope, lineTotal, updateScopeDays } = useProjectMath(form.job)

const end_date = computed(() => {
    if(!form.job.start_date) {return ''}
    let item = new Date(form.job.start_date);
    item = new Date(item.setDate(item.getDate()+form.job.days)).toLocaleDateString()
    return item
})

const states = computed(() => {
    return [{id: null, state: 'Select'}, ...props.states]
})
const uoms = computed(() => {
    return [{id: null, UOM: 'Select'}, ...props.unit_of_measurements]
})

const submitJob = (id) => {
    if (props.new) {
        form.post(route('estimating.store'), {
            onSuccess: () => {
                if (form.PDF) {
                    return window.op(route('browserPDF.pdf', { job: id }));
                }
            }
        })
    }
    else {
        form.post(route('estimating.update', { job: id, pdf: form.PDF }), {
            onSuccess: () => {
                if (form.PDF) {
                    return window.open(route('browserPDF.pdf', { job: id }));
                }
            }
        })
    }
}

onMounted(() => {
    if(form.job.start_date) {
        form.job.start_date = new Date(props.job.start_date)
    }
})

</script>

<template>
    <JobLayout>
        <Head title="Estimating Web App" />

        <v-container class="w-100 w-xl-75 bg-grey-lighten-2 rounded-lg">
            <v-row class="pa-2">
                <v-col cols="12" md="6">
                    <v-img :src="'/img/logo.png'" class="rounded-md" aria-label="company logo" />
                    <div density="compact" disabled class="text-h5">ABC Company</div>
                    <div density="compact" disabled class="text-h5">123 Main St</div>
                    <div density="compact" disabled class="text-h5">Scranton, PA 12345</div>
                </v-col>
                <template>
                    <v-card
                        class="mx-auto"
                        color="surface-variant"
                        image="https://cdn.vuetifyjs.com/docs/images/cards/dark-beach.jpg"
                        max-width="340"
                        subtitle="Take a walk down the beach"
                        title="Evening sunset"
                    >
                        <template v-slot:actions>
                        <v-btn
                            append-icon="mdi-chevron-right"
                            color="red-lighten-2"
                            text="Book Activity"
                            variant="outlined"
                            block
                        ></v-btn>
                        </template>
                    </v-card>
                </template>
                <v-col cols="12" md="6">
                    <v-text-field v-model.number="form.job.number" type="number" hide-details="auto" density="compact" label="Job Number *" class="bg-grey-lighten-4 my-1"></v-text-field>
                    <div v-if="form.errors['job.number']" class="text-red-400">{{ form.errors['job.number'] }}</div>
                    <v-text-field v-model="form.job.address" hide-details="auto" density="compact" label="Address *" class="bg-grey-lighten-4 my-1"></v-text-field>
                    <div v-if="form.errors['job.address']" class="text-red-400">{{ form.errors['job.address'] }}</div>
                    <v-text-field v-model="form.job.city" hide-details="auto" density="compact" label="City *" class="bg-grey-lighten-4 my-1"></v-text-field>
                    <div v-if="form.errors['job.city']" class="text-red-400">{{ form.errors['job.address'] }}</div>
                    <v-select v-model="form.job.state.id" hide-details="auto" :items="states" item-title="state" item-value="id" label="State *" density="compact" class="bg-grey-lighten-4 my-1"></v-select>
                    <div v-if="form.errors['job.state.id']" class="text-red-400">{{ form.errors['job.address'] }}</div>
                    <v-date-input v-model="form.job.start_date" label="Start Date" density="compact" prepend-icon="" hide-details="auto" class="bg-grey-lighten-4 my-1"></v-date-input>
                    <div v-if="form.errors['job.start_date']" class="text-red-400">{{ form.errors['job.address'] }}</div>
                    <div class="d-flex my-1 ga-1">
                        <v-text-field v-model="end_date" hide-details="auto" density="compact" label="Completion Date" class="bg-grey-lighten-4" disabled></v-text-field>
                        <v-text-field v-model="form.job.days" hide-details="auto" density="compact" label="Days *" class="bg-grey-lighten-4" disabled></v-text-field>
                    </div>
                    <v-textarea v-model="form.job.notes" hide-details="auto" label="Notes" class="bg-grey-lighten-4"></v-textarea>
                </v-col>
            </v-row>

            <form @submit.prevent="submitJob(job.id)">
                <div v-for="(scope, scopeIndex) in form.job.scopes" :key="scopeIndex" class="bg-grey-darken-1 p-2 ">
                    <div class="d-flex my-4">
                        <div class="d-flex flex-column flex-md-row w-full gap-1">
                            <v-text-field v-model="scope.name" label="Scope Name" hide-details="auto" density="compact" placeholder="Name" class="bg-grey text-black"></v-text-field>
                            <v-text-field v-model="scope.area" label="Area" hide-details="auto" density="compact" placeholder="Area" class="bg-grey text-black"></v-text-field>
                            <v-text-field v-model="scope.days" label="Days" hide-details="auto" density="compact" placeholder="Days" class="bg-grey text-black" disabled></v-text-field>
                            <v-btn prepend-icon="mdi-plus-box" @click.prevent="addScope()" v-if="!scopeIndex">Add Scope</v-btn>
                            <v-btn prepend-icon="mdi-minus" @click.prevent="removeScope(scopeIndex)" v-else>Remove Scope</v-btn>
                        </div>
                    </div>
                    <div v-if="form.errors['scopes.' + scopeIndex + '.name']" class="text-red-400">{{ form.errors['scopes.' + scopeIndex + '.name']}}</div>

                    <draggable :list="scope.lines" item-key="id" handle=".handle">
                        <template #item="{element, index}">
                            <div>

                        <!-- MD AND BELOW -->
                                <details class="bg-grey open:bg-grey-lighten-2 duration-500 rounded-md d-block d-md-none" @keyup.space="(event) => {event.preventDefault();}">
                                    <summary class="flex items-start p-1 text-lg cursor-pointer">
                                        <v-icon size="28" class="handle cursor-pointer bg-grey-darken-4 rounded ">mdi mdi-drag-horizontal-variant</v-icon>
                                        <v-text-field v-model="element.description" hide-details="auto" density="compact" label="Description *" class="bg-grey text-grey-darken-4"></v-text-field>
                                        <button class="rounded-md bg-grey-darken-4 float-right">
                                            <v-icon size="28" class="bg-grey-darken-4 " @click.prevent="addLine(scopeIndex)" v-if="!index">mdi-plus-box</v-icon>
                                            <v-icon size="28" class="bg-grey-darken-4 " @click.prevent="removeLine(scopeIndex, index)" v-else>mdi-minus</v-icon>
                                        </button>
                                    </summary>
                                    <div>
                                        <v-select v-model="element.unit_of_measurement.id" hide-details="auto" :items="uoms" density="compact" label="UOM *" class="bg-grey text-grey-darken-4" item-title="UOM" item-value="id"></v-select>
                                        <v-text-field v-model.number="element.days" hide-details="auto" type="number" density="compact" label="Days *" class="bg-grey text-grey-darken-4" @input="updateScopeDays()"></v-text-field>
                                        <v-text-field v-model.number="element.price" hide-details="auto" type="number" density="compact" label="Price *" prefix="$" class="bg-grey text-grey-darken-4" @input="lineTotal(scopeIndex, index)"></v-text-field>
                                        <v-text-field v-model.number="element.quantity" hide-details="auto" type="number" density="compact" label="Quantity *" class="bg-grey text-grey-darken-4" @input="lineTotal(scopeIndex, index)"></v-text-field>
                                        <v-text-field v-model="element.formatted_total" hide-details="auto" density="compact" label="Line Total" disabled class="bg-grey text-grey-darken-4 text-black"></v-text-field>
                                    </div>
                                </details>

                                <!-- LG AND UP -->
                                <div class="d-none d-md-flex d-md-flex-column align-center">
                                    <v-icon size="36" class="handle cursor-pointer bg-grey-lighten-2 rounded-md mr-2">mdi mdi-drag-horizontal-variant</v-icon>
                                    <v-text-field v-model="element.description" hide-details="auto" density="compact" label="Description *" class="bg-grey-lighten-1 w-60"></v-text-field>
                                    <v-select v-model="element.unit_of_measurement.id" hide-details="auto" :items="uoms" density="compact" label="UOM *" class="bg-grey-lighten-1 w-40" item-title="UOM" item-value="id"></v-select>
                                    <v-text-field v-model.number="element.days" hide-details="auto" type="number" density="compact" label="Days *" class="bg-grey-lighten-1 text-grey-darken-4 w-24" @input="updateScopeDays()"></v-text-field>
                                    <v-text-field v-model.number="element.price" hide-details="auto" type="number" density="compact" label="Price *" prefix="$" class="bg-grey-lighten-1 w-32" @input="lineTotal(scopeIndex, index)"></v-text-field>
                                    <v-text-field v-model.number="element.quantity" hide-details="auto" type="number" density="compact" label="Quantity *" class="bg-grey-lighten-1 w-32" @input="lineTotal(scopeIndex, index)"></v-text-field>
                                    <v-text-field v-model="element.formatted_total" hide-details="auto" density="compact" label="Line Total" disabled class="bg-grey-lighten-1 w-32"></v-text-field>
                                    <v-icon size="40" class="bg-grey-darken-4 text-white" @click.prevent="addLine(scopeIndex)" v-if="!index">mdi-plus-box</v-icon>
                                    <v-icon size="40" class="bg-grey-darken-4 text-white" @click.prevent="removeLine(scopeIndex, index)" v-else>mdi-minus</v-icon>
                                </div>
                                <div v-if="form.errors['job.scopes.' + scopeIndex + '.lines.' + index + '.description']" class="text-red-400">{{ form.errors['job.scopes.' + scopeIndex + '.lines.' + index + '.description']}}</div>
                                <div v-if="form.errors['job.scopes.' + scopeIndex + '.lines.' + index + '.price']" class="text-red-400">{{ form.errors['job.scopes.' + scopeIndex + '.lines.' + index + '.price']}}</div>
                                <div v-if="form.errors['job.scopes.' + scopeIndex + '.lines.' + index + '.quantity']" class="text-red-400">{{ form.errors['job.scopes.' + scopeIndex + '.lines.' + index + '.quantity']}}</div>
                                <div v-if="form.errors['job.scopes.' + scopeIndex + '.lines.' + index + '.unit_of_measurement.id']" class="text-red-400">{{ form.errors['job.scopes.' + scopeIndex + '.lines.' + index + '.unit_of_measurement.id']}}</div>
                            </div>
                        </template>
                    </draggable>

                    <div class="flex justify-end mt-2">
                        <v-text-field v-model="scope.formatted_total" hide-details="auto" density="compact" label="Scope Total" disabled class="bg-grey max-w-48 text-black"></v-text-field>
                    </div>
                </div>
                <div class="flex justify-end my-2">

                    <div density="compact" disabled label="ABC Company" class="text-h5">{{ form.job.formatted_total }}</div>
                    <!-- <v-text-field v-model="form.job.formatted_total" hide-details="auto" density="compact" label="Total" disabled class="bg-grey-ligt max-w-48"></v-text-field> -->
                </div>

                <div class="flex justify-end">
                    <div class="flex gap-2">
                        <Link :href="route('estimating.index')">
                            <v-btn type="button">Cancel</v-btn>
                        </Link>
                        <v-btn as="submit" type="submit" @click="form.PDF = false">Save & Return</v-btn>
                        <v-btn as="submit" type="submit" :disabled="form.processing">Generate PDF</v-btn>
                    </div>
                </div>
            </form>
        </v-container>

    </JobLayout>
</template>
