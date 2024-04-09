<template>
    <div class="flex flex-col">
        <div class="text-sm font-semibold text-gray-900 mb-2">Images</div>
        <div class="flex w-full space-x-4 overflow-x-scroll pb-2">
            <div class="flex-shrink-0" v-for="image in existingImagesProp" :key="image.id">
                <ProductImagePreview :imageSrc="'/storage/product_images/' + product.id + '/' + image.path"
                    @remove="removeFromExistingImages(image)" />
                <div class="flex space-x-2 justify-end items-center mt-2.5">
                    <label class="text-xs text-gray-700 font-semibold">thumbnail</label>
                    <input v-model="thumbnailProp" type="radio" :checked="thumbnailProp.id === image.id"
                        :value="{ type: 'existing', id: image.id }" class="w-4 h-4">
                </div>
            </div>
            <div class="relative flex-shrink-0" v-for="(image, index) in uploadedImagesProp" :key="index">
                <ProductImagePreview :imageSrc="image.preview" @remove="removeFromUploadedImages(image)" />
                <div class="flex space-x-2 justify-end items-center mt-2.5">
                    <label class="text-xs text-gray-700 font-semibold">thumbnail</label>
                    <input v-model="thumbnailProp" type="radio" :checked="thumbnailProp.id === image.id"
                        :value="{ type: 'new', id: image.id }" class="w-4 h-4">
                </div>
            </div>
            <div class="flex items-start justify-start flex-shrink-0">
                <label for="dropzone-file"
                    class="flex items-center justify-center w-32 h-32 border-2 border-gray-500 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                    <svg class="w-12 h-12" xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                        viewBox="0 0 24 24">
                        <path fill="#999"
                            d="M5 21q-.825 0-1.412-.587T3 19V5q0-.825.588-1.412T5 3h8q.425 0 .713.288T14 4q0 .425-.288.713T13 5H5v14h14v-8q0-.425.288-.712T20 10q.425 0 .713.288T21 11v8q0 .825-.587 1.413T19 21zM17 7h-1q-.425 0-.712-.288T15 6q0-.425.288-.712T16 5h1V4q0-.425.288-.712T18 3q.425 0 .713.288T19 4v1h1q.425 0 .713.288T21 6q0 .425-.288.713T20 7h-1v1q0 .425-.288.713T18 9q-.425 0-.712-.288T17 8zm-5.75 9L9.4 13.525q-.15-.2-.4-.2t-.4.2l-2 2.675q-.2.25-.05.525T7 17h10q.3 0 .45-.275t-.05-.525l-2.75-3.675q-.15-.2-.4-.2t-.4.2zm.75-4" />
                    </svg>
                    <input id="dropzone-file" type="file" accept="image/png, image/jpeg, image/jpg" class="hidden"
                        @change="handleImageUpload" multiple />
                </label>
            </div>
        </div>
        <InputError :message="errorMessage" />
    </div>
</template>
<script setup>
import { useProductImageUpload } from '@/Composables/useProductImageUpload';
import ProductImagePreview from '@/Components/ProductImagePreview.vue';
import InputError from '@/Components/InputError.vue';

const props = defineProps({
    product: Object,
    errorMessage: String
});

const thumbnailProp = defineModel('thumbnail');

const existingImagesProp = defineModel('existingImages');

const uploadedImagesProp = defineModel('uploadedImages');

const removedImageIdsProp = defineModel('removedImageIds');

const { uploadedImages, uploadImage, removeImage } = useProductImageUpload();

function handleImageUpload(event) {
    const images = Array.from(event.target.files).filter(image => /^image\/(jpg|jpeg|png)/.test(image.type));

    for (const image of images) {
        const newImageId = existingImagesProp.value.length + uploadedImagesProp.value.length + 1;
        uploadImage(image, newImageId);
        uploadedImagesProp.value = uploadedImages;
    }
}

function removeFromExistingImages(image) {
    if (canRemoveImage()) {
        existingImagesProp.value = existingImagesProp.value.filter(img => img.id !== image.id);
        removedImageIdsProp.value.push(image.id);
    }
}

function removeFromUploadedImages(image) {
    if (canRemoveImage()) {
        removeImage(image);
    }
}

function canRemoveImage() {
    return uploadedImagesProp.value.length + existingImagesProp.value.length >= 2;
}
</script>