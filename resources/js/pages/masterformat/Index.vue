<script setup lang="ts">
import { ref, computed, shallowRef, useTemplateRef } from 'vue';
import { Head } from '@inertiajs/vue3';
import { Input } from '@/components/ui/input';
import { Search } from 'lucide-vue-next';
import GuestLayout from '@/layouts/GuestLayout.vue';
import { useFuse } from '@vueuse/integrations/useFuse';
import { refDebounced } from '@vueuse/core'
import { UseFuseOptions } from '@vueuse/integrations';

interface DataItem {
    id: number | string
    name: string
    code: string
    category: 'division' | 'section' | 'subsection'
    division?: any
    section?: any
    csi_section?: any[]
    csi_subsection?: any[]
}

const props = defineProps({
    divisions: Object,
    sections: Object,
    subsections: Object,
})


// Static data: plain consts, not ref(), so no reactive proxying
const divisionById = new Map(props.divisions.map(d => [d.id, d]))
const sectionById  = new Map(props.sections.map(s => [s.id, s]))
const subsectionById = new Map(props.subsections.map(s => [s.id, s]))

console.log(divisionById)
console.log(sectionById)
console.log(subsectionById)

const sectionsByDivision = new Map<number, any[]>()
props.sections.forEach(s => {
    if (!sectionsByDivision.has(s.csi_division_id)) sectionsByDivision.set(s.csi_division_id, [])
    sectionsByDivision.get(s.csi_division_id)!.push(s)
})

const subsectionsBySection = new Map<number, any[]>()
props.subsections.forEach(s => {
    if (!subsectionsBySection.has(s.csi_section_id)) subsectionsBySection.set(s.csi_section_id, [])
    subsectionsBySection.get(s.csi_section_id)!.push(s)
})

// One flat search index: only what Fuse needs, plus an id and type for lookup
const searchItems = [
    ...props.divisions.map(d => ({
        type: 'division', id: d.id, name: d.name, fullCode: d.code,
    })),
    ...props.sections.map(s => ({
        type: 'section', id: s.id, name: s.name,
        fullCode: `${divisionById.get(s.csi_division_id)?.code} ${s.code}`,
    })),
    ...props.subsections.map(s => {
        const sec = sectionById.get(s.csi_section_id)
        return {
            type: 'subsection', id: s.id, name: s.name,
            fullCode: `${divisionById.get(sec?.csi_division_id)?.code} ${sec?.code} ${s.code}`,
        }
    }),
]

const search = shallowRef('')
const debouncedSearch = refDebounced(search, 150)

const { results } = useFuse(debouncedSearch, searchItems, {
    fuseOptions: {
        keys: [
            { name: 'name', weight: 0.7 },
            { name: 'fullCode', weight: 0.3 },
        ],
        threshold: 0.3,
        ignoreLocation: true,
        minMatchCharLength: 2,
    },
    resultLimit: 20,
    matchAllWhenSearchEmpty: false,
})

const selectSearch = async ({ item }) => {
    if (item.type === 'division') {
        div.value = divisionById.get(item.id)
        section.value = sectionsByDivision.get(div.value.id)?.[0] ?? null
    } else if (item.type === 'section') {
        section.value = sectionById.get(item.id)
        div.value = divisionById.get(section.value.csi_division_id)
    } else {
        const sub = subsectionById.get(item.id)
        section.value = sectionById.get(sub.csi_section_id)
        div.value = divisionById.get(section.value.csi_division_id)
        subsection.value = sub
    }
    await nextTick()
    document.querySelector(`#division-${div.value.code}`)?.scrollIntoView()
    document.querySelector(`#section-${section.value?.code}`)?.scrollIntoView()
    search.value = ''
}

// const div = ref(null)
// const section = ref(null)
// const subsection = ref(null)
// const sections = computed(() => {
//     return props.divisions.find(item => item.id == div.value?.id)?.csi_section
// })
// const subsections = computed(() => {
//     return section.value?.csi_subsection
// })

// const data = ref([])
// props.divisions.forEach(item => {
//     let object = {}
//     object.id = item.id
//     object.name = item.name
//     object.code = item.code
//     object.category = 'division'
//     object.csi_section = item.csi_section
//     data.value.push(object)
// })
// props.sections.forEach(item => {
//     let object = {}
//     object.name = item.name
//     object.code = item.code
//     object.category = 'section'
//     object.division = item.csi_division
//     data.value.push(object)
// })
// props.subsections.forEach(item => {
//     let object = {}
//     object.name = item.name
//     object.code = item.code
//     object.category = 'subsection'
//     object.csi_section = item.csi_section
//     object.division = item.csi_section.csi_division
//     data.value.push(object)
// })

// const keys = computed(() => {
//     return ['name']
// })

// const options = computed<UseFuseOptions<DataItem>>(() => ({
//     fuseOptions: {
//         keys: keys.value
//     }
// }))

// const search = shallowRef('')
// const focus = ref(false)
// const { results } = useFuse(search, data, options);
// const secSection = useTemplateRef('sectionSection')

// const selectDivision = (division) => {
//     section.value = null
//     div.value = division;
//     section.value = div.value.csi_section[0];
//     secSection.value.scrollTop = 0
// }

const filteredResults = computed(() => {
    return search.value ? results.value : []
})

// const selectSearch = async (searchedItem) => {
//     if(searchedItem.item.category === 'division') {
//         selectDivision(searchedItem.item)
//         document.querySelector('#division-' + div.value.code).scrollIntoView()
//     } else if(searchedItem.item.category === 'section') {
//         section.value = props.sections.find(section => section.name == searchedItem.item.name)
//         div.value = section.value.csi_division
//         await
//         document.querySelector('#division-' + div.value.code).scrollIntoView()
//         document.querySelector('#section-' + section.value.code).scrollIntoView()
//     } else if(searchedItem.item.category === 'subsection') {
//         subsection.value = props.subsections.find(subsection => subsection.name == searchedItem.item.name)
//         section.value = subsection.value.csi_section
//         div.value = subsection.value.csi_section.csi_division
//         await
//         console.log(div.value)
//         console.log(section.value)
//         console.log(subsection.value)
//         document.querySelector('#division-' + div.value.code).scrollIntoView()
//         document.querySelector('#section-' + section.value.code).scrollIntoView()
//         // document.querySelector('#subsection-' + subsection.value.code).scrollIntoView()
//         // console.log(searchedItem);
//     }
//     search.value = ''
//     focus.value = !focus.value
// }

</script>

<template>
    <Head title="Masterformat" />

    <GuestLayout title="Masterformat (CSI) Reference" :show-sidebar="true">
        <div class="antialiased bg-light-tertiary dark:bg-dark-tertiary" v-motion-fade>
            <main class="p-4 h-auto py-20 md:px-10">
                <div class="relative w-full items-center">
                    <Input v-model="search" class="pl-10 bg-light-secondary dark:bg-dark-secondary" />
                    <span class="absolute start-0 inset-y-0 flex items-center justify-center px-2">
                        <Search class="size-6" />
                    </span>
                </div>
                <div class="relative ms-9 ">
                    <div class="absolute bg-light-secondary w-full overflow-auto max-h-80 border-x-2 z-10" ref="resultSection" v-motion-fade v-if="results.length > 0">
                        <div v-for="(result, index) in filteredResults" :key="index" class="py-0.5 px-4 cursor-pointer hover:bg-light-tertiary hover:italic hover:font-bold hover:text-md w-full" @click.prevent="selectSearch(result)" :class="{'font-bold text-sm': result.item.category == 'division'}">
                            {{ result.item.division?.code }} {{ result.item.code }} - {{ result.item.name }} - {{ result.item.category }}
                        </div>
                    </div>
                </div>
                <!-- <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-8">
                    <div>
                        <h1 class="text-lg uppercase">Division</h1>
                        <div class="bg-light-tertiary border-2 border-gray-500 rounded-md dark:border-gray-600 h-96 overflow-auto px-4 py-1" ref="divisionSection">
                            <div v-for="(division, index) in props.divisions" :key="index" class="cursor-pointer" :id="`division-${division.code}`">
                                <div class="p-1 rounded-sm" @click="selectDivision(division)" :class="{'bg-light-primary': div?.id == division.id}">
                                    {{ division.code }} - {{ division.name }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-lg uppercase">Section</h1>
                        <div class="bg-light-tertiary border-2 rounded-md border-gray-500 dark:border-gray-600 h-96 overflow-auto px-4 py-1" ref="sectionSection">
                            <div v-for="(ref_section, i) in sections" :key="i" class="cursor-pointer" :id="`section-${ref_section.code}`">
                                <div class="p-1 rounded-sm" @click="section = ref_section" :class="{'bg-light-primary': section?.id == ref_section.id}">
                                    {{ div.code }} {{ ref_section.code }} - {{ ref_section.name }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-lg uppercase">Subsection</h1>
                        <div class="bg-light-tertiary border-2 rounded-md border-gray-500 dark:border-gray-600 h-96 overflow-auto px-4 py-1" ref="subsectionSection">
                            <div class="p-1 rounded-sm" v-for="(ref_subsection, subsectionIndex) in subsections" :key="subsectionIndex" :id="`${ref_subsection.code}`">
                                {{ div?.code }} {{ section?.code }} {{ ref_subsection?.code }} - {{ ref_subsection?.name }}
                            </div>
                        </div>
                    </div>
                </div> -->
            </main>
        </div>
    </GuestLayout>
</template>