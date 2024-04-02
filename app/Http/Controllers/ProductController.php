<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductCollection;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Services\ProductExportService;
use App\Services\ProductService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Response;

class ProductController extends Controller
{
    public function __construct(public ProductService $productService, public ProductExportService $productExportService)
    {
    }

    public function export()
    {
        return $this->productExportService->exportToExcel('test-file');
    }

    public function index(Request $request): Response
    {
        Gate::authorize('product.viewAny');

        $searchInput = $request->input('search');
        $selectedFilters = $request->input('filters');
        $sortColumn = $request->input('sort_column', 'created_at');
        $sortDirection = $request->input('sort_direction', 'asc');

        $products = $this->productService->searchAndFilter($searchInput, $selectedFilters, $sortColumn, $sortDirection);

        $priceFilter = $this->productService->calculatePriceIntervals($products->items());
        $categoryFilter = $this->productService->extractUniqueCategories($products->items());

        $totalProducts = $this->productService->getTotalProductsCount();
        $totalRevenue = $this->productService->getTotalRevenue();

        return inertia('Products/Index', [
            'products' => new ProductCollection($products),
            'totalProducts' => $totalProducts,
            'totalRevenue' => $totalRevenue,
            'searchInput' => $searchInput,
            'filters' => ['price' => $priceFilter, 'category' => $categoryFilter],
            'selectedFilters' => $selectedFilters,
            'sortColumn' => $sortColumn,
            'sortDirection' => $sortDirection,
        ]);
    }

    public function create(): Response
    {
        Gate::authorize('product.create');

        return inertia('Products/Create', [
            'categories' => ProductCategory::get(['id', 'name']),
        ]);
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();

        $this->productService->storeProduct($validatedData);

        return to_route('products.index');
    }

    public function show(Product $product): Response
    {
        Gate::authorize('product.view');

        return inertia('Products/Show', compact('product'));
    }

    public function edit(Product $product): Response
    {
        Gate::authorize('product.update');

        $product->setAttribute('base64Images', $product->base64Images());

        return inertia('Products/Edit', [
            'product' => $product,
            'categories' => ProductCategory::get(['id', 'name']),
        ]);
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $this->productService->updateProduct($product, $request->validated());

        return to_route('products.index');
    }

    public function confirmDelete(Product $product): Response
    {
        return inertia('Products/Delete', compact('product'));
    }

    public function destroyMany($products): RedirectResponse
    {
        Gate::authorize('product.delete');

        $productIds = explode(',', $products);

        $this->productService->deleteProducts($productIds);

        return to_route('products.index');
    }
}
