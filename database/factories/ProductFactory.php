<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'description' => fake()->paragraph(),
            'price' => fake()->randomFloat(2, 10, 20),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Product $product) {
            $url = 'https://picsum.photos/400/300';

            $imageContents = file_get_contents($url, false, stream_context_create([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ],
            ]));

            if ($imageContents === false) {
                dd('image error');
            } else {
                $imageName = Str::random(10);
                $imagePath = "product_images/$product->id/$imageName.jpg";
                Storage::put($imagePath, $imageContents);
                $product->images()->create(['path' => basename($imagePath)]);
            }
        });
    }
}
