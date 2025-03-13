<script setup>
import { Head } from '@inertiajs/vue3';
import Card from '@/Components/Portfolio/Card.vue';
import Header from '@/Components/Portfolio/Header.vue';
import PortfolioHeader from '@/Layouts/PortfolioHeader.vue';

</script>

<template>
        <Head title="Portfolio" />

        <PortfolioHeader />

        <div class="snap-y snap-mandatory h-screen overflow-y-scroll">
            <div class="snap-start bg-light-quatrenary h-screen overflow-hidden" id="vue-flipcard">
                <Header :title="'Nifty Cards'"></Header>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-0 sm:h-screen sm:container sm:mx-auto">
                    <div class="flex items-center justify-center">
                        <Card :front="'Spades'" :name="'spade'" :iconBack="true" class="bg-light-tail-500 hover:bg-gray-400"></Card>
                    </div>
                    <div class="flex items-center justify-center">
                        <Card :front="'Clubs'" :name="'club'" :iconBack="true" class="bg-light-tail-500 hover:bg-gray-400"></Card>
                    </div>
                    <div class="flex items-center justify-center">
                        <Card :front="'Hearts'" :name="'heart'" :iconBack="true" class="bg-light-tail-500 hover:bg-red-500"></Card>
                    </div>
                    <div class="flex items-center justify-center">
                        <Card :front="'Diamonds'" :name="'diamond'" :iconBack="true" class="bg-light-tail-500 hover:bg-red-500"></Card>
                    </div>
                </div>
            </div>
            
        </div>

</template>