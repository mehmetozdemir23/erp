<template>
    <AppHead title="Orders" />
    <AuthenticatedLayout>
        <section class="bg-gray-50 p-3 sm:p-5 antialiased">
            <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
                <div class="w-full grid grid-cols-2 gap-4">
                    <OrderCard v-for="order in orders.data" :key="order.id" :order="order"/>
                </div>
            </div>
        </section>
    </AuthenticatedLayout>
</template>
<script setup>
import AppHead from '@/Components/AppHead.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import OrderCard from '@/Pages/Orders/Partials/Card.vue';

const props = defineProps({
    orders: Object
});

</script>
