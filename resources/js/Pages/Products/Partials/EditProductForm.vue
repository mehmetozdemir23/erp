<template>
    <div class="flex flex-col h-full overflow-hidden">
        <form class="flex flex-col h-full p-4 overflow-hidden" @submit.prevent="handleProductUpdate">
            <div class="flex items-center justify-between mb-4 border-b rounded-t">
                <h3 class="flex items-center text-2xl font-bold text-gray-900">
                    <img :src="currentThumbnailPreview" alt="Product thumbnail"
                        class="h-14 w-auto object-cover mr-6 rounded">
                    {{ product.name }}
                </h3>
                <Link :href="route('products.index')"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
                <span class="sr-only">Close modal</span>
                </Link>
            </div>
            <div class="flex-1 flex flex-col overflow-y-scroll">
                <div class="grid gap-4 grid-cols-2 mb-4">
                    <div class="col-span-2">
                        <label for="name" class="block mb-2 text-sm font-semibold text-gray-900">Name</label>
                        <input type="text" name="name" id="name" v-model="form.name"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5"
                            placeholder="Type product name" required>
                        <InputError :message="form.errors.name" />
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label for="price" class="block mb-2 text-sm font-semibold text-gray-900">Price</label>
                        <div class="relative">
                            <div class="absolute left-2 top-1/2 -translate-y-1/2">$</div>
                            <input type="number" name="price" id="price" v-model="form.price" step="0.01"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full pl-6 pr-2.5 py-2"
                                placeholder="$2999" required>
                        </div>
                        <InputError :message="form.errors.price" />
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label for="category" class="block mb-2 text-sm font-semibold text-gray-900">Category</label>
                        <select id="category" v-model="form.category_id"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option v-for="category in categories" :key="category.id" :value="category.id"
                                :selected="category.id === form.category_id">
                                {{ category.name }}
                            </option>
                        </select>
                        <InputError :message="form.errors.category_id" />
                    </div>
                    <div class="col-span-2">
                        <label for="description"
                            class="block mb-2 text-sm font-semibold text-gray-900">Description</label>
                        <textarea id="description" rows="4" v-model="form.description"
                            class="resize-none block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Write product description here"></textarea>
                        <InputError :message="form.errors.description" />
                    </div>
                </div>
                <div class="flex flex-col mb-4">
                    <div class="text-sm font-semibold text-gray-900 mb-2">Images</div>
                    <div class="flex space-x-2 overflow-x-scroll w-auto">
                        <label for="dropzone-file"
                            class="flex-shrink-0 flex flex-col items-center justify-center w-32 h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                            <div class="flex flex-col items-center justify-center p-5">
                                <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2">
                                    </path>
                                </svg>
                                <p class="text-xs text-gray-500 text-center dark:text-gray-400">PNG or JPG<br>(MAX. 5MB)
                                </p>
                            </div>
                            <input id="dropzone-file" type="file" multiple accept="image/*" class="hidden"
                                @change="handleImageUpload">
                        </label>
                        <div class="relative flex-shrink-0" v-for="image in form.uploadedImages" :key="image.id">
                            <label :for="'thumbnailRadio' + image.id">
                                <ProductImagePreview :image-src="image.url" @remove="handleImageRemove(image)" />
                                <input type="radio" :id="'thumbnailRadio' + image.id"
                                    :value="{ type: 'new', id: image.id }" v-model="form.thumbnail"
                                    :checked="form.thumbnail.id === image.id" class="absolute top-2 right-2">
                            </label>
                        </div>
                        <div class="relative flex-shrink-0" v-for="image in existingImages" :key="image.id">
                            <label :for="'thumbnailRadio' + image.id">
                                <ProductImagePreview
                                    :image-src="'/storage/product_images/' + product.id + '/' + image.path"
                                    @remove="handleImageRemove(image)" />
                                <input type="radio" :id="'thumbnailRadio' + image.id"
                                    :value="{ type: 'existing', id: image.id }" v-model="form.thumbnail"
                                    :checked="form.thumbnail.id === image.id" class="absolute top-2 right-2">
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-full flex justify-end items-center space-x-4 pt-4 border-t">
                <Link :href="route('products.index')" class="text-sm font-bold text-gray-500">Cancel</Link>
                <button type="submit"
                    class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-semibold rounded-full text-sm px-5 py-2.5 text-center">
                    Update product
                </button>
            </div>
        </form>
    </div>
</template>
<script setup>
import { ref, computed } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
import { useProducts } from '@/Composables/useProducts';
import InputError from '@/Components/InputError.vue';
import ProductImagePreview from '@/Components/ProductImagePreview.vue';

const { product } = defineProps({
    product: Object,
    categories: Array
});

const { id, name, description, price, category: { id: category_id } } = product;

const existingImages = ref(product.images);

const thumbnailIndex = existingImages.value.findIndex(img => img.is_thumbnail);

if (thumbnailIndex !== -1) {
    const thumbnailImage = existingImages.value.splice(thumbnailIndex, 1)[0];
    existingImages.value.unshift(thumbnailImage);
}

const currentThumbnailPreview = computed(() => {
    const { type, id } = form.thumbnail;
    const thumbnails = type === 'new' ? form.uploadedImages : existingImages.value;
    const thumbnail = thumbnails.find(img => img.id === id);

    if (thumbnail) {
        return type === 'new' ? thumbnail.url : `/storage/product_images/${product.id}/${thumbnail.path}`;
    } else {
        return '';
    }
});

const form = useForm({
    id,
    name,
    description,
    price,
    category_id,
    uploadedImages: [],
    removedImageIds: [],
    thumbnail: { type: 'existing', id: existingImages.value[0].id }
});

function handleImageUpload(event) {
    const files = event.target.files;
    for (const file of files) {
        const url = URL.createObjectURL(file);
        const maxId = Math.max(...form.uploadedImages.map(img => img.id), ...existingImages.value.map(img => img.id));
        const id = maxId !== -Infinity ? maxId + 1 : 1;
        form.uploadedImages.push({ id, file, url });
    }
};

function handleImageRemove(image) {
    if (!canRemoveImage()) return;

    const isUploaded = form.uploadedImages.findIndex(img => img.id === image.id) !== -1;

    if (isUploaded) {
        form.uploadedImages = form.uploadedImages.filter(img => img.id !== image.id);
    } else {
        existingImages.value = existingImages.value.filter(img => img.id !== image.id);
        form.removedImageIds.push(image.id);
    }

    if (form.thumbnail && form.thumbnail.id === image.id) {
        if (existingImages.value.length > 0 && (isUploaded || form.uploadedImages.length === 0)) {
            form.thumbnail = { type: 'existing', id: existingImages.value[0].id };
        } else if (form.uploadedImages.length > 0) {
            form.thumbnail = { type: 'new', id: form.uploadedImages[0].id };
        } else {
            form.thumbnail = null;
        }
    }
}

function canRemoveImage() {
    return existingImages.value.length + form.uploadedImages.length >= 2;
}

function handleProductUpdate() {
    useProducts().updateProduct(product, form)
}
</script>