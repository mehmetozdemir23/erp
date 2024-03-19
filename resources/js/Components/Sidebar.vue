<template>
    <div>
        <!-- Sidebar -->
        <aside :class="isVisible ? 'translate-x-0 shadow-gray-400' : '-translate-x-full shadow-white bg-white'"
            class="fixed top-0 left-0 z-30 w-60 h-screen pt-14 shadow-md bg-white transition-transform border-r border-gray-200 lg:translate-x-0 lg:bg-white lg:shadow-none">
            <!-- Sidebar content -->
            <div class="overflow-y-auto py-5 px-3 h-full">
                <ul class="space-y-2">
                    <li>
                        <Link :href="route('products.index')"
                            class="flex items-center p-2 text-base font-semibold text-gray-900 rounded-lg hover:bg-gray-100 group">
                        <svg class="flex-shrink-0 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="M19 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2m0 16H5V5h14zM9 17H7v-5h2zm4 0h-2v-7h2zm4 0h-2V7h2z" />
                        </svg>
                        <span class="ml-3">Overview</span>
                        </Link>
                    </li>
                    <li>
                        <Link :href="route('products.index')"
                            class="flex items-center p-2 text-base font-semibold text-gray-900 rounded-lg hover:bg-gray-100 group">
                        <svg class="flex-shrink-0 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900"
                            xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="M5 22q-.825 0-1.412-.587T3 20V8.725q-.45-.275-.725-.712T2 7V4q0-.825.588-1.412T4 2h16q.825 0 1.413.588T22 4v3q0 .575-.275 1.013T21 8.724V20q0 .825-.587 1.413T19 22zM5 9v11h14V9zM4 7h16V4H4zm5 7h6v-2H9zm3 .5" />
                        </svg>
                        <span class="ml-3">Products</span>
                        </Link>
                    </li>
                    <li>
                        <Link :href="route('orders.index')"
                            class="flex items-center p-2 text-base font-semibold text-gray-900 rounded-lg hover:bg-gray-100 group">
                        <svg class="flex-shrink-0 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900"
                            xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 14 14">
                            <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                <path
                                    d="M9.5 1.5H11a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1v-10a1 1 0 0 1 1-1h1.5" />
                                <rect width="5" height="2.5" x="4.5" y=".5" rx="1" />
                                <path d="M4.5 5.5h5M4.5 8h5m-5 2.5h5" />
                            </g>
                        </svg>
                        <span class="ml-3">Orders</span>
                        </Link>
                    </li>
                </ul>
            </div>
        </aside>

        <!-- Overlay for the remaining space -->
        <div v-show="isVisible" @click="closeSidebar" class="fixed top-0 left-0 z-20 w-full h-full bg-black opacity-30">
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
defineProps({
    isVisible: Boolean
});

const emit = defineEmits(['closed']);

function closeSidebar() {
    emit('closed');
}
</script>