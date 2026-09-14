<script setup lang="ts">
import { Users as UsersIcon } from 'lucide-vue-next';
import UserDrawer from '../drawers/UserDrawer.vue';

const props = defineProps({
    users: Object,
    roles: Object,
})

const columns = 'grid grid-cols-[1.2fr_1.6fr_1fr_auto]'

</script>

<template>
    <div class="flex h-full flex-col">

        <!-- Header -->
        <div class="flex items-center gap-3">
            <div
                class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border-2 border-black bg-white text-black shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] dark:border-white dark:bg-black/20 dark:text-white"
            >
                <UsersIcon class="h-4 w-4" :stroke-width="2.5" />
            </div>
            <p class="font-mono text-[11px] font-bold uppercase tracking-widest text-black/40 dark:text-white/40">
                Users
            </p>
        </div>

        <!-- Column headers -->
        <div :class="[columns, 'mt-3 gap-3 border-b-2 border-black/10 pb-1.5 dark:border-white/10']">
            <span class="font-mono text-[10px] font-bold uppercase tracking-widest text-black/40 dark:text-white/40">User</span>
            <span class="font-mono text-[10px] font-bold uppercase tracking-widest text-black/40 dark:text-white/40">Email</span>
            <span class="font-mono text-[10px] font-bold uppercase tracking-widest text-black/40 dark:text-white/40">Roles</span>
            <span class="text-center font-mono text-[10px] font-bold uppercase tracking-widest text-black/40 dark:text-white/40">Edit</span>
        </div>

        <!-- Rows -->
        <div class="mt-1.5 flex min-h-0 flex-1 flex-col gap-1 overflow-y-auto">
            <p
                v-if="!props.users.length"
                class="rounded-xl border-2 border-dashed border-black/20 p-4 text-center text-xs font-bold text-black/30 dark:border-white/20 dark:text-white/30"
            >
                No users yet
            </p>

            <div
                v-for="(user, index) in props.users" :key="index"
                :class="[columns, 'items-center gap-3 rounded-xl border-2 border-black bg-white px-3 py-1 dark:border-white dark:bg-black/20']"
            >
                <span class="truncate text-xs font-bold text-black dark:text-white">{{ user.name }}</span>
                <span class="truncate text-xs font-medium text-black/60 dark:text-white/60">{{ user.email }}</span>

                <div class="flex flex-wrap gap-1">
                    <span
                        v-for="(role, i) in user.roles" :key="i"
                        class="inline-flex items-center rounded-md border border-black/20 bg-light-quatrenary px-1.5 py-0.5 font-mono text-[10px] font-bold uppercase tracking-wide text-black dark:border-white/20 dark:bg-dark-tertiary dark:text-white"
                    >
                        {{ role.name }}
                    </span>
                </div>

                <div class="flex justify-center">
                    <UserDrawer :user :roles></UserDrawer>
                </div>
            </div>
        </div>
    </div>
</template>