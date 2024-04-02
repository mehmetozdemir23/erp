<template>
    <div class="text-sm font-semibold text-center text-gray-500 border-b border-gray-300 mb-4">
        <ul class="flex flex-wrap -mb-px">
            <li v-for="(status, index) in orderStatuses" :key="index">
                <Link :href="route('orders.index')" :data="{ status }"
                    :class="isActiveStatus(status) ? 'border-blue-500 text-blue-600' : 'border-gray-300 text-gray-600'"
                    class="inline-block p-4 border-b-2 rounded-t-lg capitalize">{{ status }}
                </Link>
            </li>
        </ul>
    </div>
</template>
<script setup>
import { Link } from '@inertiajs/vue3';
import orderStatuses from '@/Enums/orderStatuses';

const props = defineProps({
    activeStatus: String
});

function isActiveStatus(status) {
    return props.activeStatus === status;
}

</script>@/Enums/orderStatuses