<?php

namespace App\Services;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ProductService
{
    public function getAllProducts(int $perPage = 10): LengthAwarePaginator
    {
        return Product::with(['createdBy', 'tags', 'images'])
            ->latest()
            ->paginate($perPage);
    }

    public function createProduct(array $data)
    {
        return DB::transaction(function () use ($data) {
            $user = User::first() ?? User::factory()->create([
                'email' => 'default@example.com',
                'password' => bcrypt('password'),
            ]);

            $product = Product::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'price' => $data['price'],
                'unit' => $data['unit'],
                'quantity' => $data['quantity'],
                'created_by_id' => $user->id,
            ]);

            if (isset($data['tags'])) {
                $product->tags()->attach($data['tags']);
            }

            return $product;
        });
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