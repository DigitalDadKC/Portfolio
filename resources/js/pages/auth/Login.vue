<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Input } from '@/components/ui/input';
import { Label } from 'reka-ui';
import { Checkbox } from '@/components/ui/checkbox';
import PrimaryButton from '@/components/PrimaryButton.vue';
import FormattedInput from '@/components/FormattedInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import ApplicationLogo from '@/components/ApplicationLogo.vue';
import { ArrowUpRight } from 'lucide-vue-next';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Log in" />

    <div class="relative flex min-h-screen items-center justify-center overflow-hidden bg-light-primary px-4 py-12 dark:bg-dark-primary">

        <!-- Decorative background, echoes Hero -->
        <div
            class="
                pointer-events-none
                absolute -right-32 -top-32
                hidden
                h-96 w-96
                rounded-full
                border-2 border-black/10
                dark:border-white/10
                lg:block
            "
        />
        <div
            class="
                pointer-events-none
                absolute -bottom-40 -left-32
                hidden
                h-96 w-96
                rounded-full
                bg-light-quatrenary/20
                dark:bg-dark-tertiary/20
                lg:block
            "
        />

        <div class="relative w-full max-w-md">

            <!-- Logo -->
            <div class="mb-8 flex justify-center">
                <Link href="/" class="group inline-flex items-center gap-3">
                    <div
                        class="
                            flex shrink-0
                            items-center justify-center
                            rounded-xl
                            border-2 border-black
                            bg-light-quatrenary
                            p-1.5
                            shadow-[3px_3px_0px_0px_rgba(0,0,0,1)]
                            transition-transform
                            duration-200
                            group-hover:-translate-y-0.5
                            dark:border-white
                        "
                    >
                        <ApplicationLogo />
                    </div>
                    <span class="text-xl font-extrabold tracking-tight text-black dark:text-white">
                        Digital<span class="text-accent">Dad</span>KC
                    </span>
                </Link>
            </div>

            <!-- Status banner -->
            <div
                v-if="status"
                class="
                    mb-6
                    rounded-xl
                    border-2 border-black
                    bg-light-quatrenary
                    px-4 py-3
                    text-sm font-bold
                    text-black
                    shadow-[3px_3px_0px_0px_rgba(0,0,0,1)]
                    dark:border-white
                    dark:text-white
                "
            >
                {{ status }}
            </div>

            <!-- Card -->
            <div
                class="
                    rounded-2xl
                    border-2 border-black
                    bg-white
                    p-6
                    shadow-[6px_6px_0px_0px_rgba(0,0,0,1)]
                    dark:border-white
                    dark:bg-dark-secondary
                    sm:p-8
                "
            >
                <div class="mb-6">
                    <p class="text-xs font-bold uppercase tracking-widest text-black/40 dark:text-white/40">
                        Welcome back
                    </p>
                    <h1 class="mt-1 text-2xl font-extrabold text-black dark:text-white">
                        Log in to your account
                    </h1>
                </div>

                <form @submit.prevent="submit" class="space-y-5">
                    <div>
                        <Label
                            for="email"
                            class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-black/60 dark:text-white/60"
                        >
                            Email
                        </Label>
                        <Input
                            id="email"
                            width="full"
                            v-model="form.email"
                            class="rounded-xl border-2 border-black px-4 py-2.5 font-medium focus-visible:ring-0 focus:border-accent dark:border-white"
                            autofocus
                            autocomplete="username"
                        />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div>
                        <Label
                            for="password"
                            class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-black/60 dark:text-white/60"
                        >
                            Password
                        </Label>
                        <Input
                            id="password"
                            type="password"
                            width="full"
                            v-model="form.password"
                            class="rounded-xl border-2 border-black px-4 py-2.5 font-medium focus-visible:ring-0 focus:border-accent dark:border-white"
                            autocomplete="current-password"
                        />
                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2">
                            <Checkbox
                                v-model:checked="form.remember"
                                class="rounded-md border-2 border-black data-[state=checked]:bg-accent dark:border-white"
                            />
                            <span class="text-sm font-bold text-black/60 dark:text-white/60">Remember me</span>
                        </label>

                        <Link
                            v-if="canResetPassword"
                            :href="route('password.request')"
                            class="
                                text-sm font-bold text-black/60
                                underline decoration-2 underline-offset-2
                                transition-colors
                                hover:text-accent
                                dark:text-white/60
                                dark:hover:text-accent
                            "
                        >
                            Forgot password?
                        </Link>
                    </div>

                    <PrimaryButton
                        type="submit"
                        :disabled="form.processing"
                        :class="{ 'opacity-40': form.processing }"
                        class="
                            group
                            inline-flex w-full
                            items-center justify-center
                            gap-2
                            rounded-xl
                            border-2 border-black
                            bg-black
                            px-6 py-3
                            text-base font-extrabold
                            text-white
                            shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]
                            transition-[transform,box-shadow,background-color,color]
                            duration-200
                            hover:-translate-y-1
                            hover:bg-accent
                            hover:text-black
                            hover:shadow-[6px_6px_0px_0px_rgba(0,0,0,1)]
                            active:translate-y-0
                            active:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]
                            disabled:pointer-events-none
                            dark:border-white
                            dark:bg-white
                            dark:text-black
                            dark:hover:bg-accent
                        "
                    >
                        Log in
                        <ArrowUpRight
                            class="
                                h-4 w-4
                                transition-transform
                                duration-200
                                group-hover:translate-x-1
                                group-hover:-translate-y-1
                            "
                            :stroke-width="2.5"
                        />
                    </PrimaryButton>
                </form>
            </div>
        </div>
    </div>
</template>