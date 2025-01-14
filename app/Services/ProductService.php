<?php

namespace App\Services;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductService
{
    public function getAllProducts(int $perPage = 10): LengthAwarePaginator
    {
        return Product::with(['createdBy', 'tags', 'images'])
            ->latest()
            ->paginate($perPage);
    }

    public function createProduct(array $data): Product
    {
        // Get the first user as default
        $defaultUser = User::first();
        if (!$defaultUser) {
            throw new \Exception('No default user found. Please run UserSeeder first.');
        }
        
        $data['created_by_id'] = $defaultUser->id;
        
        $product = Product::create($data);

        if (isset($data['tags'])) {
            $product->tags()->sync($data['tags']);
        }

        return $product->load(['createdBy', 'tags', 'images']);
    }

    public function updateProduct(Product $product, array $data): Product
    {
        $product->update($data);

        if (isset($data['tags'])) {
            $product->tags()->sync($data['tags']);
        }

        return $product->load(['createdBy', 'tags', 'images']);
    }

    public function deleteProduct(Product $product): bool
    {
        return $product->delete();
    }

    public function findProduct(string $id): ?Product
    {
        return Product::with(['createdBy', 'tags', 'images'])->find($id);
    }
} 