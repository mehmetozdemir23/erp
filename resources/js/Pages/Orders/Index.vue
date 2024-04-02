<template>
    <AppHead title="Orders" />
    <AuthenticatedLayout>
        <div class="mx-auto max-w-screen-xl flex-1 flex flex-col">
            <h1 class="text-2xl font-semibold mb-4">
                {{ $page.component.split('/')[0] }}
            </h1>
            <StatusTabs :activeStatus="status" />
            <section class="flex-1 overflow-y-scroll bg-gray-50 antialiased">
                <div class="w-full grid md:grid-cols-2 gap-4">
                    <OrderCard v-for="order in orders.data" :key="order.id" :order="order" />
                </div>
            </section>
        </div>
    </AuthenticatedLayout>
</template>
<script setup>
import AppHead from '@/Components/AppHead.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import OrderCard from '@/Pages/Orders/Partials/Card.vue';
import StatusTabs from '@/Pages/Orders/Partials/StatusTabs.vue';

const props = defineProps({
    orders: Object,
    status: String
});

</script>
