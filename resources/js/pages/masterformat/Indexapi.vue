<script setup lang="ts">
import GuestLayout from '@/layouts/GuestLayout.vue';

// const response = await fetch('/api/masterformat', {
//     headers: {
//         Accept: 'application/json',
//     }
// })

const { data } = await axios.get('/api/masterformat')

console.log(data)
</script>

<template>
    idiot
    <GuestLayout>
        idiot
    </GuestLayout>
</template>