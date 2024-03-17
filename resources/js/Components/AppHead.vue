<template>
    <Head :title="title">
        <slot />
    </Head>
</template>
<script setup>
import { Head } from '@inertiajs/vue3';
defineProps({
    title: {
        type: String,
        default: ''
    }
});
</script>