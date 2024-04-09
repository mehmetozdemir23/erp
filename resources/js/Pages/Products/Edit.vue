<template>
    <AppHead :title="product.name" />
    <Modal>
        <Form :product="product" :categories="categories" />
    </Modal>
</template>

<script setup>
import Form from '@/Pages/Products/Partials/EditProductForm.vue';
import Modal from '@/Components/Modal.vue';
import AppHead from '@/Components/AppHead.vue';

const props = defineProps({
    product: Object,
    categories: Array
});

</script>