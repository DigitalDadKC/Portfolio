<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

const showMessage = ref(false);

const form = useForm({
    name: '',
    email: '',
    body: ''
})

function setShowMessage(value) {
    showMessage.value = value
}

function cleanForm() {
    form.reset();
    setShowMessage(true)
    setTimeout(() => setShowMessage(false), 2000)
}

const submit = () => {
    form.post(route('contact'), {
        onSuccess: () => cleanForm(),
        preserveScroll: true
    })
}
</script>

<template>
    <section id="contact" class="section bg-light-primary dark:bg-dark-primary">
        <div class="container mx-auto" v-motion :initial="{opacity: 0, y:100}" :visibleOnce="{opacity: 1, y: 0}">
            <div class="flex flex-col items-center text-center">
                <h2 class="section-title">Contact Me</h2>
            </div>
            <div class="flex flex-col lg:flex-row lg:gap-x-8 ">
                <div class="flex flex-1 flex-col  space-y-8 mb-12 lg:mb-0 lg:pt-2">
                    <div class="flex flex-col lg:flex-row gap-x-4">
                        <div class="text-accent rounded-sm lg:w-14 h-6 flex items-start justify-center mt-2 mb-4 lg:mb-0 text-2xl">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 9v.906a2.25 2.25 0 01-1.183 1.981l-6.478 3.488M2.25 9v.906a2.25 2.25 0 001.183 1.981l6.478 3.488m8.839 2.51l-4.66-2.51m0 0l-1.023-.55a2.25 2.25 0 00-2.134 0l-1.022.55m0 0l-4.661 2.51m16.5 1.615a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V8.844a2.25 2.25 0 011.183-1.98l7.5-4.04a2.25 2.25 0 012.134 0l7.5 4.04a2.25 2.25 0 011.183 1.98V19.5z" /></svg>
                        </div>
                        <div>
                            <h4 class="font-body text-xl mb-1">Have a question?</h4>
                            <p class="mb-1 text-paragraph">I'm here to help!</p>
                            <p class="text-accent font-normal">Email me at <a class="font-bold" href="mailto:raleighgroesbeck@gmail.com" aria-label="Email Me">raleighgroesbeck@gmail.com</a></p>
                        </div>
                    </div>
                    <div class="flex flex-col lg:flex-row gap-x-4">
                        <div class="text-accent rounded-sm lg:w-14 h-6 flex items-start justify-center mt-2 mb-4 lg:mb-0 text-2xl">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                        </div>
                        <div>
                            <h4 class="font-body text-xl mb-1">Current Location</h4>
                            <p class="mb-1 text-paragraph">Lee's Summit, MO, USA</p>
                            <p class="text-accent font-normal">Serving customers nationwide</p>
                        </div>
                    </div>
                </div>
                <form @submit.prevent="submit" action="" class="space-y-8 w-full max-w-md mx-auto">
                    <div v-if="showMessage" class="py-4 text-accent dark:text-dark-quatrenary rounded-lg">
                        Thank you for contacting me!<br>
                        We'll be in touch!
                    </div>
                    <div class="flex gap-8">
                        <div>
                            <input v-model="form.name" type="text" class="input" placeholder="Your Name" aria-label="Your Name">
                            <span v-if="form.errors.name" class="text-sm m-2 text-red-400">{{ form.errors.name }}</span>
                        </div>
                        <div>
                            <input v-model="form.email" type="email" class="input" placeholder="Your Email" aria-label="Your Email">
                            <span v-if="form.errors.email" class="text-sm m-2 text-red-400">{{ form.errors.email }}</span>
                        </div>
                    </div>
                    <textarea v-model="form.body" name="" id="" cols="30" rows="10" class="textarea" placeholder="Your Message" spellcheck="false" aria-label="Your Message"></textarea>
                    <span v-if="form.errors.body" class="text-sm m-2 text-red-400">{{ form.errors.body }}</span>
                    <button class="btn btn-lg bg-accent hover:bg-secondary text-white float-right rounded-md" aria-label="Submit Message">
                        Send Message
                    </button>
                </form>
            </div>
        </div>
    </section>
</template>
