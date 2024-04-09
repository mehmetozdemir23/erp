import { ref } from "vue";

export function useProductImageUpload() {
    const uploadedImages = ref([]);

    function uploadImage(image, id) {
        const isImageUploaded = uploadedImages.value.includes(image);

        if (!isImageUploaded) {
            uploadedImages.value.push({
                id,
                file: image,
                preview: URL.createObjectURL(image),
            });
        }
    }

    function removeImage(image) {
        URL.revokeObjectURL(image);

        uploadedImages.value = uploadedImages.value.filter(
            (img) => img !== image
        );
    }

    return {
        uploadedImages,
        uploadImage,
        removeImage,
    };
}
