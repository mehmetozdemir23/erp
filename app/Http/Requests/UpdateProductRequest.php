<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('product.update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => ['required', 'exists:products,id'],
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'category_id' => ['required', 'exists:product_categories,id'],
            'thumbnail' => ['required', 'array'],
            'thumbnail.type' => ['string', 'in:new,existing'],
            'thumbnail.id' => [
                'integer',
                Rule::when(
                    fn () => $this->input('thumbnail.type') === 'existing',
                    'exists:product_images,id'
                ),
            ],
            'uploadedImages' => ['nullable', 'array'],
            'uploadedImages.*.id' => ['integer'],
            'uploadedImages.*.file' => ['image', 'mimes:jpeg,png,jpg', 'max:5120'],
            'removedImageIds' => ['nullable', 'array'],
            'removedImageIds.*' => ['exists:product_images,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The product name is required.',
            'name.string' => 'The product name must be a valid string.',
            'name.min' => 'The product name must be at least :min characters.',
            'name.max' => 'The product name cannot exceed :max characters.',

            'price.required' => 'The product price is required.',
            'price.numeric' => 'The product price must be a valid number.',
            'price.min' => 'The product price must be at least :min.',

            'description.string' => 'The product description must be a valid string.',

            'category_id.required' => 'Please select a category for the product.',
            'category_id.exists' => 'The selected category is invalid.',

            'thumbnail.required' => 'The thumbnail is required.',
            'thumbnail.array' => 'The thumbnail must be an array.',
            'thumbnail.type.string' => 'The thumbnail type must be a string.',
            'thumbnail.type.in' => 'The selected thumbnail type is invalid.',
            'thumbnail.id.integer' => 'The thumbnail ID must be an integer.',
            'thumbnail.id.exists' => 'The selected thumbnail must exist in database.',

            'uploadedImages.array' => 'The images must be provided in an array.',
            'uploadedImages.min' => 'Please upload at least :min image(s) of the product.',
            'uploadedImages.max' => 'You can upload up to :max images of the product.',

            'uploadedImages.*.required' => 'Each image must be provided.',
            'uploadedImages.*.id' => 'Image id must be integer.',
            'uploadedImages.*.file.image' => 'Please upload a valid image file.',
            'uploadedImages.*.file.mimes' => 'Incorrect file format for one or more images. Allowed formats: jpeg, png, jpg.',
            'uploadedImages.*.file.max' => 'One or more images exceeds the maximum file size of :max kilobytes.',

            'removedImageIds.array' => 'The images to be deleted must be provided in an array.',
            'removedImageIds.*.exists' => 'Each image to be deleted must exist in database.',
        ];
    }
}
