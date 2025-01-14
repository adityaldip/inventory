<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function __construct(private ProductService $productService)
    {
    }

    public function index(Request $request)
    {
        $products = $this->productService->getAllProducts($request->per_page ?? 10);
        return ProductResource::collection($products);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:products',
            'price' => 'required|numeric|min:0',
            'unit' => ['required', Rule::in(['pcs', 'kilogram', 'mililiter', 'liter', 'gram', 'ton'])],
            'quantity' => 'required|integer|min:0',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id'
        ]);

        $product = $this->productService->createProduct($validated);
        return new ProductResource($product);
    }

    public function show(Product $product)
    {
        return new ProductResource($product->load(['createdBy', 'tags', 'images']));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('products')->ignore($product->id)],
            'price' => 'sometimes|required|numeric|min:0',
            'unit' => ['sometimes', 'required', Rule::in(['pcs', 'kilogram', 'mililiter', 'liter', 'gram', 'ton'])],
            'quantity' => 'sometimes|required|integer|min:0',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id'
        ]);

        $product = $this->productService->updateProduct($product, $validated);
        return new ProductResource($product);
    }

    public function destroy(Product $product)
    {
        $this->productService->deleteProduct($product);
        return response()->noContent();
    }
} 