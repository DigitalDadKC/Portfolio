<script setup>
    import { Head } from '@inertiajs/vue3';
    import InputLabel from '@/Components/InputLabel.vue';
    import { useStorage } from "@vueuse/core";

    const showSidebar = useStorage('my-flag', true)
    const emit = defineEmits(['toggleSidebar']);
</script>

<template>
    <Head title="Estimating" />

    <v-app class="d-flex min-h-screen">
        <v-app-bar elevation="2" class="bg-grey-lighten-2">
            <template v-slot:prepend>
                <v-app-bar-nav-icon @click.stop="showSidebar = !showSidebar"></v-app-bar-nav-icon>
                <Link :href="route('home')">
                    <v-btn bg-color="red">Back to Digital Dad</v-btn>
                </Link>
            </template>
        </v-app-bar>

        <v-navigation-drawer v-model="showSidebar" :location="$vuetify.display.mobile ? 'bottom' : undefined" temporary class="d-flex flex-wrap p-4 bg-grey">
            <v-card>
                <v-list>
                    <v-list-subheader>NAVIGATION</v-list-subheader>
                    <v-list-item color="primary" rounded="shaped">
                        <template v-slot:prepend>
                            <v-icon>mdi-home</v-icon>
                        </template>
                        <v-list-item-title>
                            <Link :href="route('estimating.index')">
                                Estimating
                            </Link>
                        </v-list-item-title>
                    </v-list-item>
                    <v-list-item color="primary" rounded="shaped">
                        <template v-slot:prepend>
                            <v-icon>mdi-chart-line</v-icon>
                        </template>
                        <v-list-item-title>
                            <Link :href="route('estimating.report')">
                                Reports
                            </Link>
                        </v-list-item-title>
                    </v-list-item>
                    <v-list-item color="primary" rounded="shaped">
                        <template v-slot:prepend>
                            <v-icon>mdi-domain</v-icon>
                        </template>
                        <v-list-item-title>
                            <Link :href="route('company.index')">
                                Company Page
                            </Link>
                        </v-list-item-title>
                    </v-list-item>
                </v-list>
            </v-card>
        </v-navigation-drawer>

        <v-main class="bg-grey-lighten-1">
            <slot />
        </v-main>
    </v-app>
</template>
