<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_transaction()
    {
        Queue::fake();
        
        $product = Product::factory()->create([
            'quantity' => 100,
            'price' => 99.99
        ]);

        $response = $this->postJson('/api/v1/transactions', [
            'type' => 'OUT',
            'customer_email' => 'test@example.com',
            'customer_name' => 'Test Customer',
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 5
                ]
            ]
        ]);

        $response->assertStatus(201);
        
        $this->assertEquals(95, $product->fresh()->quantity);
        
        Queue::assertPushed(\App\Jobs\SendTransactionEmail::class);
    }

    public function test_prevents_overselling()
    {
        $product = Product::factory()->create([
            'quantity' => 10
        ]);

        $response = $this->postJson('/api/v1/transactions', [
            'type' => 'OUT',
            'customer_email' => 'test@example.com',
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 20
                ]
            ]
        ]);

        $response->assertStatus(422);
        $this->assertEquals(10, $product->fresh()->quantity);
    }
} 