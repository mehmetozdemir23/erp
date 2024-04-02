<template>
    <AppHead title="Products" />
    <AuthenticatedLayout>
        <section class="bg-gray-50 antialiased">
            <div class="mx-auto max-w-screen-xl">
                <h1 class="text-2xl font-semibold">
                    Products
                </h1>
                <div class="bg-white relative overflow-hidden">
                    <ProductTable :products="products.data.data" :total-products="totalProducts"
                        :pagination-links="products.meta.links" :total-revenue="totalRevenue" :sort-column="sortColumn"
                        :sort-direction="sortDirection" :filters="filters" :selected-filters="selectedFilters"
                        :searchInput="searchInput" />
                </div>
                <Pagination :links="links" :from="from" :to="to" :total="total" />
            </div>
        </section>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AppHead from '@/Components/AppHead.vue';
import ProductTable from '@/Pages/Products/Partials/Table.vue';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
    products: Object,
    totalProducts: Number,
    totalRevenue: String,
    sortColumn: String,
    sortDirection: String,
    filters: Object,
    selectedFilters: Object,
    searchInput: String
});

const { links, from, to, total } = props.products.meta;
</script>