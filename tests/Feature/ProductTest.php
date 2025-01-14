<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_product_with_tags()
    {
        $tag = Tag::factory()->create(['name' => 'electronics']);
        
        $response = $this->postJson('/api/v1/products', [
            'name' => 'Test Product',
            'price' => 99.99,
            'unit' => 'pcs',
            'quantity' => 100,
            'tags' => [$tag->id]
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'price',
                    'unit',
                    'quantity',
                    'tags'
                ]
            ]);
    }

    public function test_can_update_product_quantity()
    {
        $product = Product::factory()->create(['quantity' => 100]);

        $response = $this->putJson("/api/v1/products/{$product->id}", [
            'quantity' => 90
        ]);

        $response->assertStatus(200);
        $this->assertEquals(90, $product->fresh()->quantity);
    }
} 