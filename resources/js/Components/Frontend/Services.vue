<script setup>
import Card from '@/Components/Portfolio/Card.vue';
</script>


<template>
    <section id="services" class="section bg-light-secondary dark:bg-dark-secondary">
        <div class="container mx-auto">
            <div class="flex flex-col items-center text-center mb-8">
                <h2 class="section-title">Services</h2>
            </div>
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 text-center gap-16">
                <div class="flex flex-col gap-6" v-motion :initial="{ opacity: 0, y:200}" :visibleOnce="{opacity: 1, y:0}" :delay="200">
                    <Card :front="'Custom App Development'" :name="'gear'" :iconFront="true" class="hover:bg-light-quatrenary hover:dark:bg-accent"></Card>
                    <div class="flex flex-col gap-2">
                        <p>Custom app development should work for you, not against you. By far is it a more sophisticated solution than any off-the-shelf software package.</p>
                        <p>I'm able to provide a tailored solution that works exactly the way you want it to for your business.</p>
                    </div>
                </div>
                <div class="flex flex-col gap-6" v-motion :initial="{ opacity: 0, y:200}" :visibleOnce="{opacity: 1, y:0}" :delay="400">
                    <Card :front="'Mailing Services'" :name="'thumbsup'" :iconFront="true" class="hover:bg-light-quatrenary hover:dark:bg-accent"></Card>
                    <div class="flex flex-col gap-2">
                        <p>Want to be notified when a project is created or when a team member signs up?</p>
                        <p>I can provide custom mailable functionality as you see fit.</p>
                    </div>
                </div>
                <div class="flex flex-col gap-6" v-motion :initial="{ opacity: 0, y:200}" :visibleOnce="{opacity: 1, y:0}" :delay="600">
                    <Card :front="'Secure Ecosystem'" :name="'arrow'" :iconFront="true" class="hover:bg-light-quatrenary hover:dark:bg-accent"></Card>
                    <div class="flex flex-col gap-2">
                        <p>The Laravel framework provides a secure, protected development environment with custom login rules and restrictions to allow you and your team while keeping everyone else out.</p>
                    </div>
                </div>
                <div class="flex flex-col gap-6" v-motion :initial="{ opacity: 0, y:200}" :visibleOnce="{opacity: 1, y:0}" :delay="800">
                    <Card :front="'Multiple Export Options'" :name="'lightbulb'" :iconFront="true" class="hover:bg-light-quatrenary hover:dark:bg-accent"></Card>
                    <div class="flex flex-col gap-2">
                        <p>Need to streamline your deliverables?</p>
                        <p>PDF and XLS options are available</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
