<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import {
    Mail,
    MapPin,
    ArrowUpRight,
    Send,
    Check,
} from 'lucide-vue-next'

import { Label } from 'reka-ui'
import { Input } from '@/components/ui/input'
import { Button } from '@/components/ui/button'
import { Textarea } from '@/components/ui/textarea'

const showMessage = ref(false)

const form = useForm({
    name: '',
    email: '',
    body: '',
})

function cleanForm() {
    form.reset()
    showMessage.value = true

    setTimeout(() => {
        showMessage.value = false
    }, 4000)
}

const submit = () => {
    showMessage.value = false

    form.post(route('contact'), {
        onSuccess: cleanForm,
        preserveScroll: true,
    })
}
</script>

<template>
    <section
        id="contact"
        class="
            section
            relative
            overflow-hidden
            bg-light-primary
            py-20
            dark:bg-dark-primary
        "
    >
        <!-- Decorative background -->
        <div
            class="
                pointer-events-none
                absolute -left-32 top-20
                h-80 w-80
                rounded-full
                border-2 border-black/10
                dark:border-white/10
            "
        />

        <div
            class="
                pointer-events-none
                absolute -right-32 bottom-0
                h-96 w-96
                rounded-full
                bg-light-quatrenary/20
                dark:bg-dark-tertiary/20
            "
        />

        <div
            class="relative container mx-auto px-4"
            v-motion
            :initial="{ opacity: 0, y: 24 }"
            :visibleOnce="{ opacity: 1, y: 0 }"
        >
            <!-- Heading -->
            <div class="mb-12 text-center">
                <span
                    class="
                        mb-4 inline-block
                        rounded-full
                        border-2 border-black
                        bg-light-quatrenary
                        px-4 py-1
                        text-sm font-bold
                        uppercase tracking-widest
                        shadow-[3px_3px_0px_0px_rgba(0,0,0,1)]
                    "
                >
                    Let's talk
                </span>

                <h2
                    class="
                        text-4xl
                        font-extrabold
                        tracking-tight
                        text-black
                        dark:text-white
                        sm:text-5xl
                    "
                >
                    Have a project in mind?
                </h2>

                <p
                    class="
                        mx-auto mt-4
                        max-w-2xl
                        text-gray-600
                        dark:text-gray-300
                    "
                >
                    Whether you have a question, an idea, or a problem
                    that needs solving, I'd love to hear about it.
                </p>
            </div>

            <!-- Content -->
            <div
                class="
                    grid
                    gap-8
                    lg:grid-cols-[0.8fr_1.2fr]
                    lg:items-start
                "
            >
                <!-- Contact information -->
                <div class="space-y-5">

                    <!-- Email -->
                    <a
                        href="mailto:info@digitaldadkc.com"
                        class="
                            group
                            flex
                            items-start
                            gap-4
                            rounded-2xl
                            border-2 border-black
                            bg-white/70
                            p-5
                            shadow-[5px_5px_0px_0px_rgba(0,0,0,1)]
                            transition-[transform,box-shadow,background-color]
                            duration-200
                            ease-out
                            hover:-translate-y-1
                            hover:bg-light-quatrenary
                            hover:shadow-[7px_7px_0px_0px_rgba(0,0,0,1)]
                            dark:border-white
                            dark:bg-black/20
                            dark:hover:bg-dark-tertiary
                        "
                    >
                        <div
                            class="
                                flex h-12 w-12 shrink-0
                                items-center justify-center
                                rounded-xl
                                border-2 border-black
                                bg-light-quatrenary
                                transition-transform
                                duration-200
                                group-hover:rotate-[-6deg]
                                group-hover:scale-105
                                dark:border-white
                            "
                        >
                            <Mail
                                class="h-6 w-6"
                                :stroke-width="2.5"
                            />
                        </div>

                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2">
                                <h3
                                    class="
                                        font-extrabold
                                        text-lg
                                    "
                                >
                                    Have a question?
                                </h3>

                                <ArrowUpRight
                                    class="
                                        h-4 w-4
                                        opacity-0
                                        transition-opacity
                                        duration-200
                                        group-hover:opacity-100
                                    "
                                />
                            </div>

                            <p
                                class="
                                    mt-1
                                    text-sm
                                    text-gray-600
                                    dark:text-gray-300
                                "
                            >
                                I'm here to help.
                            </p>

                            <p
                                class="
                                    mt-2
                                    break-all
                                    font-bold
                                    text-accent
                                "
                            >
                                info@digitaldadkc.com
                            </p>
                        </div>
                    </a>

                    <!-- Location -->
                    <div
                        class="
                            group
                            flex
                            items-start
                            gap-4
                            rounded-2xl
                            border-2 border-black
                            bg-white/70
                            p-5
                            shadow-[5px_5px_0px_0px_rgba(0,0,0,1)]
                            transition-[transform,box-shadow]
                            duration-200
                            ease-out
                            hover:-translate-y-1
                            hover:shadow-[7px_7px_0px_0px_rgba(0,0,0,1)]
                            dark:border-white
                            dark:bg-black/20
                        "
                    >
                        <div
                            class="
                                flex h-12 w-12 shrink-0
                                items-center justify-center
                                rounded-xl
                                border-2 border-black
                                bg-light-quatrenary
                                transition-transform
                                duration-200
                                group-hover:rotate-[-6deg]
                                group-hover:scale-105
                                dark:border-white
                            "
                        >
                            <MapPin
                                class="h-6 w-6"
                                :stroke-width="2.5"
                            />
                        </div>

                        <div>
                            <h3
                                class="
                                    font-extrabold
                                    text-lg
                                "
                            >
                                Based in Kansas City
                            </h3>

                            <p
                                class="
                                    mt-1
                                    text-sm
                                    text-gray-600
                                    dark:text-gray-300
                                "
                            >
                                Serving clients nationwide.
                            </p>

                            <p
                                class="
                                    mt-2
                                    font-bold
                                    text-accent
                                "
                            >
                                Kansas City, MO
                            </p>
                        </div>
                    </div>

                    <!-- Small CTA -->
                    <div
                        class="
                            rounded-2xl
                            border-2 border-black
                            bg-black
                            p-6
                            text-white
                            shadow-[5px_5px_0px_0px_rgba(0,0,0,1)]
                            dark:border-white
                            dark:bg-white
                            dark:text-black
                        "
                    >
                        <p
                            class="
                                text-lg
                                font-extrabold
                            "
                        >
                            Got an interesting problem?
                        </p>

                        <p
                            class="
                                mt-2
                                text-sm
                                leading-6
                                text-gray-300
                                dark:text-gray-600
                            "
                        >
                            Tell me what you're working on.
                            Sometimes the weird projects are the best ones.
                        </p>
                    </div>
                </div>

                <!-- Form -->
                <div
                    class="
                        rounded-2xl
                        border-2 border-black
                        bg-white
                        p-6
                        shadow-[7px_7px_0px_0px_rgba(0,0,0,1)]
                        sm:p-8
                        dark:border-white
                        dark:bg-black/20
                    "
                >
                    <!-- Success -->
                    <Transition name="fade">
                        <div
                            v-if="showMessage"
                            class="
                                mb-6
                                flex
                                items-start
                                gap-3
                                rounded-xl
                                border-2 border-black
                                bg-light-quatrenary
                                p-4
                                dark:border-white
                            "
                        >
                            <div
                                class="
                                    flex h-8 w-8 shrink-0
                                    items-center justify-center
                                    rounded-full
                                    border-2 border-black
                                    bg-white
                                    dark:border-white
                                    dark:bg-black
                                "
                            >
                                <Check
                                    class="h-4 w-4"
                                    :stroke-width="3"
                                />
                            </div>

                            <div>
                                <p class="font-extrabold">
                                    Message sent!
                                </p>

                                <p
                                    class="
                                        mt-1
                                        text-sm
                                        text-gray-600
                                        dark:text-gray-300
                                    "
                                >
                                    Thanks for reaching out. I'll be in touch!
                                </p>
                            </div>
                        </div>
                    </Transition>

                    <div class="mb-6">
                        <h3
                            class="
                                text-2xl
                                font-extrabold
                            "
                        >
                            Send me a message
                        </h3>

                        <p
                            class="
                                mt-1
                                text-sm
                                text-gray-500
                                dark:text-gray-400
                            "
                        >
                            I'll get back to you as soon as I can.
                        </p>
                    </div>

                    <form
                        class="space-y-5"
                        @submit.prevent="submit"
                    >
                        <!-- Name + Email -->
                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <Label
                                    for="name"
                                    class="mb-2 block font-bold"
                                >
                                    Name
                                </Label>

                                <Input
                                    id="name"
                                    v-model="form.name"
                                    autocomplete="name"
                                    class="
                                        border-2
                                        border-black
                                        bg-light-tertiary
                                        dark:border-white
                                        dark:bg-dark-tertiary
                                    "
                                    :aria-invalid="!!form.errors.name"
                                />

                                <span
                                    v-if="form.errors.name"
                                    class="
                                        mt-1
                                        block
                                        text-sm
                                        font-medium
                                        text-red-500
                                    "
                                >
                                    {{ form.errors.name }}
                                </span>
                            </div>

                            <div>
                                <Label
                                    for="email"
                                    class="mb-2 block font-bold"
                                >
                                    Email
                                </Label>

                                <Input
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    autocomplete="email"
                                    class="
                                        border-2
                                        border-black
                                        bg-light-tertiary
                                        dark:border-white
                                        dark:bg-dark-tertiary
                                    "
                                    :aria-invalid="!!form.errors.email"
                                />

                                <span
                                    v-if="form.errors.email"
                                    class="
                                        mt-1
                                        block
                                        text-sm
                                        font-medium
                                        text-red-500
                                    "
                                >
                                    {{ form.errors.email }}
                                </span>
                            </div>
                        </div>

                        <!-- Message -->
                        <div>
                            <Label
                                for="body"
                                class="mb-2 block font-bold"
                            >
                                Message
                            </Label>

                            <Textarea
                                id="body"
                                v-model="form.body"
                                :rows="8"
                                class="
                                    border-2
                                    border-black
                                    bg-light-tertiary
                                    dark:border-white
                                    dark:bg-dark-tertiary
                                "
                                :aria-invalid="!!form.errors.body"
                            />

                            <span
                                v-if="form.errors.body"
                                class="
                                    mt-1
                                    block
                                    text-sm
                                    font-medium
                                    text-red-500
                                "
                            >
                                {{ form.errors.body }}
                            </span>
                        </div>

                        <!-- Submit -->
                        <Button
                            type="submit"
                            :disabled="form.processing"
                            class="
                                group
                                w-full
                                border-2
                                border-black
                                bg-black
                                py-6
                                text-base
                                font-extrabold
                                text-white
                                shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]
                                transition-[transform,box-shadow]
                                duration-200
                                hover:-translate-y-0.5
                                hover:shadow-[6px_6px_0px_0px_rgba(0,0,0,1)]
                                disabled:cursor-not-allowed
                                disabled:opacity-60
                                dark:border-white
                                dark:bg-white
                                dark:text-black
                            "
                        >
                            <span v-if="!form.processing">
                                Send message
                            </span>

                            <span
                                v-else
                                class="flex items-center justify-center gap-2"
                            >
                                Sending...
                            </span>

                            <Send
                                v-if="!form.processing"
                                class="
                                    ml-2
                                    h-4 w-4
                                    transition-transform
                                    duration-200
                                    group-hover:translate-x-1
                                    group-hover:-translate-y-1
                                "
                                :stroke-width="2.5"
                            />
                        </Button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition:
        opacity 200ms ease,
        transform 200ms ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
    transform: translateY(-6px);
}
</style>