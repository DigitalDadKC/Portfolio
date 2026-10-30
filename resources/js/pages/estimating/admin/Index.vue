<script setup lang="ts">
import {  } from "vue";
import { Head } from "@inertiajs/vue3";
import EstimatingLayout from '@/layouts/EstimatingLayout.vue';
import Users from './widgets/Users.vue';
import Company from './widgets/Company.vue';
import LargestProposal from "./widgets/LargestProposal.vue";
import RecentProposal from "./widgets/RecentProposal.vue";
import DoughnutChart from "./widgets/DoughnutChart.vue";
import CurrentMonthlyRevenue from "./widgets/CurrentMonthlyRevenue.vue";
import LineChart from "./widgets/LineChart.vue";
import ProjectHeader from "@/components/ProjectHeader.vue";
import { UserGroup } from "@lucide/vue";

const props = defineProps({
    new: Boolean,
    company: Object,
    jobs: Object,
    proposals: Object,
    states: Object,
    users: Object,
    roles: Object,
    skills: Object,
})

const cardClass = 'rounded-2xl border-2 border-black bg-light-primary p-4 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] dark:border-white dark:bg-dark-primary'

</script>

<template>
    <EstimatingLayout>

        <Head title="Admin" />

        <main class="mx-auto w-full max-w-7xl px-4 py-8">

            <ProjectHeader
                title="Admin"
                subtitle="Construction Software"
                :total="props.users.length"
                total_category="Users"
            >
                Custom-built dashboard showing user administration and a quick overview of your estimating progress.
                <template #icon>
                    <UserGroup class="size-5" />
                </template>
            </ProjectHeader>

            <div class="grid grid-cols-1 gap-4 mb-4 lg:grid-cols-2 xl:grid-cols-4">
                <div :class="[cardClass, 'h-60']">
                    <LargestProposal :jobs></LargestProposal>
                </div>
                <div :class="[cardClass, 'h-60']">
                    <RecentProposal :proposals></RecentProposal>
                </div>
                <div :class="[cardClass, 'h-60']">
                    <CurrentMonthlyRevenue :jobs></CurrentMonthlyRevenue>
                </div>
                <div :class="[cardClass, 'h-60']">
                    <Company :company :states></Company>
                </div>
            </div>
            <div class="grid grid-cols-1 gap-4 mb-4 md:grid-cols-2 xl:grid-cols-3">
                <div :class="[cardClass, 'h-96 p-6']">
                    <Users :users :roles></Users>
                </div>
                <div :class="[cardClass, 'h-96 p-6']">
                    <DoughnutChart :users :jobs></DoughnutChart>
                </div>
                <div :class="[cardClass, 'h-96 p-6']">
                    <LineChart :jobs></LineChart>
                </div>
            </div>
        </main>

    </EstimatingLayout>
</template>