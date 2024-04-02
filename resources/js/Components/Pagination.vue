<template>
    <div v-if="links.length > 3">
        <nav class="flex flex-col items-start justify-between p-4 space-y-3 md:flex-row md:items-center md:space-y-0"
            aria-label="Table navigation">
            <span class="text-sm font-normal text-gray-500">
                Showing
                <span class="font-semibold text-gray-900">{{ `${from}-${to}` }}</span>
                of
                <span class="font-semibold text-gray-900">{{ total }}</span>
            </span>
            <div class="flex flex-wrap">
                <template v-for="(link, key) in links" :key="key">
                    <div v-if="link.url === null"
                        class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border rounded"
                        v-html="link.label" />

                    <Link v-else
                        class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded focus:border-blue-500 focus:text-blue-500"
                        :class="{ 'bg-blue-700 text-white': link.active }" :href="link.url" v-html="link.label" />
                </template>
            </div>
        </nav>

    </div>
</template>
<script setup>
import { Link } from '@inertiajs/vue3';
const props = defineProps({
    links: Object,
    from: Number,
    to: Number,
    total: Number
});
</script>