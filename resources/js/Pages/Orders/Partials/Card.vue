<template>
    <div class="w-full bg-white rounded-lg shadow-md p-6 border border-gray-700">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Order #{{ order.id }}</h2>
        <div class="flex justify-between items-center mb-4">
            <span class="text-gray-600">Date:</span>
            <span class="text-gray-800">{{ order.created_at }}</span>
        </div>
        <div class="flex justify-between items-center mb-4">
            <span class="text-gray-600">Customer:</span>
            <span class="text-gray-800">{{ order.customer }}</span>
        </div>
        <div class="flex justify-between items-center mb-4">
            <span class="text-gray-600">Total:</span>
            <span class="text-gray-800">${{ order.total_amount }}</span>
        </div>
        <div class="flex justify-end">
            <button class="bg-blue-500 hover:bg-blue-600 text-gray-700 font-semibold py-2 rounded">
                View Details
            </button>
        </div>
    </div>
</template>
<script setup>
const props = defineProps({
    order: Object
});

console.log(props.order);
</script>