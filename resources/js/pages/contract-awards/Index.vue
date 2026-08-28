<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { Head } from '@inertiajs/vue3';
import GuestLayout from '@/layouts/GuestLayout.vue';
import FilterDropdown from '@/components/FilterDropdown.vue';
import { useFormatCurrency } from '@/composables/useFormatCurrency';
import { useOffsetPagination, useDateFormat } from '@vueuse/core';
import { Accordion, AccordionContent, AccordionItem, AccordionTrigger } from '@/components/ui/accordion'
import BidInput from '@/components/estimating/BidInput.vue';

const { formatWithCommas } = useFormatCurrency()
const props = defineProps({
    results: Object,
})

console.log(props.results)

</script>

<template>
    <Head title="Contract Awards Example" />

    <GuestLayout title="Sam.gov Contract Awards API Example">
        <main class="flex justify-center p-4 h-auto py-20 md:px-10">
            <div>
                <p>{{ props.results.awardSummary.length }} records</p>
                <ul>Parameters:
                    <li>NAICS code: 236220</li>
                    <li>Results cached for 12 hours</li>
                </ul>
            </div>
            <div>
                <div v-for="result in props.results.awardSummary" :key="result.contractId?.piid">
                    <!-- <p>{{result.awardDetails.awardeeData.awardeeUEIInformation.awardeeUltimateParentName}}</p>
                    <p>{{ result.contractId.piid }}</p> -->
                    <!-- <p>{{ formatWithCommas(result.awardDetails.dollars.actionObligation, 'currency') }}</p>
                    <p>{{ formatWithCommas(result.awardDetails.dollars.baseAndAllOptionsValue, 'currency') }}</p>
                    <p>{{ formatWithCommas(result.awardDetails.dollars.baseAndExercisedOptionsValue, 'currency') }}</p>
                    <p>{{ formatWithCommas(result.awardDetails.dollars.baseDollarsObligated, 'currency') }}</p>
                    <p>{{ formatWithCommas(result.awardDetails.dollars.feePaidForUseOfService, 'currency') }}</p> -->
                    <p>{{ result.awardDetails.awardeeData.awardeeHeader }}</p>
                    <br />
                </div>
            </div>
        </main>
    </GuestLayout>
</template>