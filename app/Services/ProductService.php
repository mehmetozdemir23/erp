<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use App\Models\ProductStock;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductService
{
    public function searchAndFilter(
        ?string $searchInput = null,
        ?array $filters = null,
        string $sortColumn = 'created_at',
        string $sortDirection = 'asc'
    ): LengthAwarePaginator {
        $query = Product::with([
            'orderItems.order:id,status',
            'thumbnail:id,path,product_id',
            'category:id,name',
            'stock:id,product_id,quantity',
        ]);

        $this->applySearchFilter($query, $searchInput);
        $this->applyCategoryFilter($query, $filters);
        $this->applyPriceFilter($query, $filters);
        $this->applySorting($query, $sortColumn, $sortDirection);

        return $query->paginate(5)->withQueryString();
    }

    protected function applySearchFilter($query, ?string $searchInput): void
    {
        if ($searchInput) {
            $query->where('products.name', 'LIKE', $searchInput.'%');
        }
    }

    protected function applyCategoryFilter($query, ?array $filters): void
    {
        $categoryFilter = $filters['category'] ?? null;
        if ($categoryFilter && $categoryFilter !== 'all') {
            $query->whereHas('category', function ($query) use ($categoryFilter) {
                $query->where('name', $categoryFilter);
            });
        }
    }

    protected function applyPriceFilter($query, ?array $filters): void
    {
        $priceFilter = $filters['price'] ?? null;
        if ($priceFilter && $priceFilter !== 'all') {
            [$minPrice, $maxPrice] = explode('-', $priceFilter);
            $query->whereBetween('price', [$minPrice, $maxPrice]);
        }
    }

    protected function applySorting($query, string $sortColumn, string $sortDirection): void
    {
        if ($sortColumn === 'category') {
            $query->join('product_categories', 'products.product_category_id', '=', 'product_categories.id')
                ->orderBy('product_categories.name', $sortDirection)
                ->select('products.*');
        } elseif ($sortColumn === 'stock') {
            $query->join('product_stocks', 'products.id', '=', 'product_stocks.product_id')
                ->orderBy('quantity', $sortDirection)
                ->select('products.*');
        } elseif ($sortColumn === 'salesCount') {
            $this->orderBySalesCount($query, $sortDirection);
        } elseif ($sortColumn === 'revenue') {
            $this->orderByRevenue($query, $sortDirection);
        } else {
            $query->orderBy($sortColumn, $sortDirection);
        }
    }

    protected function orderBySalesCount($query, string $sortDirection = 'asc'): void
    {
        $query->withSum(
            [
                'orderItems as sales_count' => function ($query) {
                    $query->select(DB::raw('sum(quantity)'))
                        ->whereHas('order', function ($query) {
                            $query->whereHas('sale');
                        });
                },
            ],
            'sales_count'
        )->orderBy('sales_count', $sortDirection);
    }

    protected function orderByRevenue($query, string $sortDirection = 'asc'): void
    {
        $query->withSum(
            [
                'orderItems as sales_count' => function ($query) {
                    $query->select(DB::raw('sum(quantity)'))
                        ->whereHas('order', function ($query) {
                            $query->whereHas('sale');
                        });
                },
            ],
            'sales_count'
        )->orderByRaw("sales_count * price $sortDirection");
    }

    public function storeProduct(array $attributes)
    {
        $product = new Product($attributes);

        $category = ProductCategory::find($attributes['category_id']);

        $product->category()->associate($category);

        $product->save();

        $product->stock()->save(new ProductStock());

        $productImages = $attributes['images'];
        foreach ($productImages as $key => $image) {
            $imageModel = new ProductImage(['path' => basename($image->storeAs("product_images/$product->id", Str::random(10).'.'.$image->extension()))]);
            $product->images()->save($imageModel);
        }
    }

    public function updateProduct(Product $product, array $attributes)
    {
        $product->update($attributes);

        $removedImageIds = $attributes['removedImageIds'] ?? [];
        foreach ($removedImageIds as $removedImageId) {
            $this->deleteProductImage($product, $removedImageId);
        }

        $this->uploadProductImages($product, $attributes['uploadedImages'] ?? []);

        $this->setProductThumbnail($product, $attributes['thumbnail'] ?? []);

    }

    private function deleteProductImage(Product $product, $imageId)
    {
        $image = ProductImage::find($imageId);
        if ($image) {
            Storage::delete("public/product_images/{$product->id}/{$image->path}");
            $image->delete();
        }
    }

    private function uploadProductImages(Product $product, $uploadedImages)
    {
        foreach ($uploadedImages as $uploadedImage) {
            $file = $uploadedImage['file'];
            $imageModel = new ProductImage(['path' => basename($file->storeAs("public/product_images/{$product->id}", Str::random(10).'.'.$file->extension()))]);
            $product->images()->save($imageModel);
        }
    }

    private function setProductThumbnail(Product $product, $thumbnail)
    {
        $thumbnailType = $thumbnail['type'] ?? null;
        $thumbnailId = $thumbnail['id'] ?? null;

        if ($thumbnailType === 'new') {
            $product->images()->where('id', $thumbnailId)->update(['is_thumbnail' => true]);
            $product->images()->where('id', '!=', $thumbnailId)->update(['is_thumbnail' => false]);
        } elseif ($thumbnailType === 'existing' && $product->thumbnail->id !== $thumbnailId) {
            $product->images()->where('id', $thumbnailId)->update(['is_thumbnail' => true]);
            $product->images()->where('id', '!=', $thumbnailId)->update(['is_thumbnail' => false]);
        }

        $product->refresh();
    }

    public function deleteProducts(array $productIds)
    {
        foreach ($productIds as $productId) {
            $product = Product::find($productId);
            if ($product) {
                $product->delete();
            }
        }
    }

    public function calculatePriceIntervals(array $products): array
    {
        $products = collect($products);

        $minPrice = floor($products->min('price') / 10) * 10;
        $maxPrice = ceil($products->max('price') / 10) * 10;

        $intervalsCount = 5;
        $intervalSize = ($maxPrice - $minPrice) / $intervalsCount;

        $priceFilter = [];
        for ($i = 0; $i < $intervalsCount; $i++) {
            $lowerBound = $minPrice + $i * $intervalSize;
            $upperBound = $lowerBound + $intervalSize;

            $lowerBound = number_format($lowerBound, 2);
            $upperBound = number_format($upperBound, 2);

            $priceFilter[] = "$lowerBound-$upperBound";
        }

        return $priceFilter;
    }

    public function extractUniqueCategories(array $products): array
    {
        return collect($products)->pluck('category.name')->unique()->values()->toArray();
    }

    public function getTotalProductsCount(): int
    {
        return Product::count();
    }

    public function getTotalRevenue(): string
    {
        return Product::totalRevenue();
    }
}
