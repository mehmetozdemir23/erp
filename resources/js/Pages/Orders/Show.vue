<template>
    <AppHead :title="'Order #' + order.id" />
    <AuthenticatedLayout>
        <div class="mx-auto max-w-screen-xl flex-1 flex flex-col">
            <h1 class="text-2xl font-semibold mb-4">
                Order #{{ order.id }}
            </h1>
        </div>
    </AuthenticatedLayout>
</template>
<script setup>
import AppHead from '@/Components/AppHead.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineProps({
    order: Object
});
</script>
