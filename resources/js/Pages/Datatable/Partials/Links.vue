<script setup>
import { watch } from 'vue';
import InputLabel from '@/Components/InputLabel.vue';
import FilterDropdown from '@/Components/FilterDropdown.vue';

const props = defineProps({
    pages: Number,
    currentPage: Number,
    currentPageSize: Number,
    isFirstPage: Boolean,
    isLastPage: Boolean
})

let currentPage = props.currentPage
let records = props.currentPageSize

const emit = defineEmits(['changePage', 'changeRecords']);

const changePage = (page) => {
    currentPage = page
    emit('changePage', currentPage)
}

watch(() => props.pages, () => {
    currentPage = props.currentPage
})

const changeRecords = (item) => {
    records = item
    emit('changeRecords', records)
}

</script>

<template>
    <div class="font-bold text-light-quatrenary w-full flex items-stretch justify-stretch py-2">
        <div v-if="props.pages < 10">
            <button :disabled="isFirstPage" class="rounded-l-md px-6 py-2 rounded-l-md" :class="{'bg-light-tertiary': isFirstPage, 'bg-light-secondary hover:bg-light-tertiary': !isFirstPage}" @click="currentPage--; $emit('changePage', currentPage)">prev</button>
            <button v-for="item in props.pages" :key="item" :disabled="currentPage === item" class="px-6 py-2" :class="{'bg-light-quatrenary text-white': currentPage === item, 'bg-light-secondary hover:bg-light-tertiary': currentPage !== item}" @click="currentPage = item; $emit('changePage', item)">{{ item }}</button>
            <button :disabled="currentPage === props.pages" class="px-6 py-2 rounded-r-md" :class="{'bg-light-tertiary': isLastPage, 'bg-light-secondary hover:bg-light-tertiary': !isLastPage}" @click="currentPage++; $emit('changePage', currentPage)">next</button>
        </div>
        <div v-else>
            <button :disabled="isFirstPage" class="rounded-l-md px-6 py-2 rounded-l-md" :class="{'bg-light-tertiary': isFirstPage, 'bg-light-secondary hover:bg-light-tertiary': !isFirstPage}" @click="currentPage--; $emit('changePage', currentPage)">prev</button>
            <button class="px-6 py-2" :class="{'bg-light-quatrenary text-white': currentPage === 1, 'bg-light-secondary hover:bg-light-tertiary': currentPage !== 1}" @click="changePage(1)">1</button>
            <button class="px-6 py-2" :class="{'bg-light-quatrenary text-white': currentPage === 2, 'bg-light-secondary hover:bg-light-tertiary': currentPage !== 2}" @click="changePage(2)">2</button>
            <span v-if="![3].includes(currentPage)">...</span>
            <div v-for="item in props.pages" :key="item" class="inline-flex">
                <button v-if="(currentPage == item) && ![1, 2].includes(currentPage) && ![props.pages, props.pages-1].includes(currentPage)" :disabled="currentPage === item" class="px-6 py-2" :class="{'bg-light-quatrenary text-white': currentPage === item, 'bg-light-secondary hover:bg-light-tertiary': currentPage !== item}" @click="changePage()">{{ item }}</button>
            </div>
            <span>...</span>
            <button class="px-6 py-2" :class="{'bg-light-quatrenary text-white': currentPage === props.pages-1, 'bg-light-secondary hover:bg-light-tertiary': currentPage !== props.pages-1}" @click="changePage(props.pages-1)">{{ props.pages-1 }}</button>
            <button class="px-6 py-2" :class="{'bg-light-quatrenary text-white': currentPage === props.pages, 'bg-light-secondary hover:bg-light-tertiary': currentPage !== props.pages}" @click="changePage(props.pages)">{{props.pages}}</button>
            <button :disabled="currentPage === props.pages" class="px-6 py-2 rounded-r-md" :class="{'bg-light-tertiary': isLastPage, 'bg-light-secondary hover:bg-light-tertiary': !isLastPage}" @click="currentPage++; $emit('changePage', currentPage)">next</button>
        </div>
    </div>
    <div class="flex justify-end gap-2">
        <InputLabel for="pageCount" value="Records Per Page" class="text-xl text-light-primary truncate" />
        <FilterDropdown :options="[{'count': 10 }, {'count': 25 }, {'count': 50}, {'count': 100}]" :column="'count'" :value="records" @change="changeRecords(+$event.target.value)"></FilterDropdown>
    </div>
</template>