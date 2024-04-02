<template>
    <div class="w-full bg-white rounded-lg shadow-md p-6 border border-gray-700">
        <h2 class="flex justify-between items-center text-lg font-semibold text-gray-800 mb-4">
            <span>
                Order #{{ order.id }}
            </span>
            <span :class="getStatusColor(order.status)" class="px-3 py-1 text-sm rounded">{{ order.status }}</span>
        </h2>
        <div class="flex justify-between items-center mb-4">
            <span class="text-gray-600">Date</span>
            <span class="text-gray-800 font-semibold">{{ order.created_at }}</span>
        </div>
        <div class="flex justify-between items-center mb-4">
            <span class="text-gray-600">Customer</span>
            <span class="text-gray-800 font-semibold">{{ order.customer }}</span>
        </div>
        <div class="flex justify-between items-center mb-6">
            <span class="text-gray-600">Total</span>
            <span class="text-gray-800 font-semibold">${{ Number(order.total_amount).toFixed(2) }}</span>
        </div>
        <div class="flex justify-end">
            <Link :href="route('orders.show', { order })" class="text-blue-500 text-sm font-semibold hover:underline">
            View Details
            </Link>
        </div>
    </div>
</template>
<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    order: Object
});

function getStatusColor(status) {
    if (status === 'pending') {
        return 'bg-gray-300 text-black';
    }
    if (status === 'completed') {
        return 'bg-green-600 text-white';
    }
    if (status === 'cancelled') {
        return 'bg-yellow-500 text-white';
    }
    if (status === 'rejected') {
        return 'bg-red-600 text-white';
    }
}
</script>