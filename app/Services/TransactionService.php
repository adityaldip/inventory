<?php

namespace App\Services;

use App\Events\TransactionCompleted;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionService
{
    public function createTransaction(array $data): Transaction
    {
        return DB::transaction(function () use ($data) {
            // Get default user if not authenticated
            $userId = auth()->id() ?? User::first()->id;

            // Create transaction
            $transaction = Transaction::create([
                'type' => $data['type'],
                'customer_email' => $data['customer_email'] ?? null,
                'customer_name' => $data['customer_name'] ?? null,
                'supplier_name' => $data['supplier_name'] ?? null,
                'notes' => $data['notes'] ?? null,
                'created_by_id' => $userId,
            ]);

            $totalAmount = 0;

            // Process each item
            foreach ($data['items'] as $item) {
                $product = Product::lockForUpdate()->find($item['product_id']);
                
                if (!$product) {
                    throw new \Exception("Product not found: {$item['product_id']}");
                }

                $quantityBefore = $product->quantity;
                $newQuantity = $this->calculateNewQuantity($product->quantity, $item['quantity'], $data['type']);
                
                // Update product quantity
                $product->update(['quantity' => $newQuantity]);

                // Create transaction item
                $transactionItem = $transaction->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'total_amount' => $product->price * $item['quantity'],
                    'quantity_before' => $quantityBefore,
                    'quantity_after' => $newQuantity,
                    'created_by_id' => $userId,
                ]);

                $totalAmount += $transactionItem->total_amount;
            }

            // Update transaction total
            $transaction->update(['total_amount' => $totalAmount]);

            // Dispatch event for email notification
            if ($transaction->customer_email) {
                TransactionCompleted::dispatch($transaction);
            }

            return $transaction->load(['items.product', 'createdBy']);
        }, 5); // 5 retries for deadlock
    }

    private function calculateNewQuantity(int $current, int $change, string $type): int
    {
        return match ($type) {
            'IN' => $current + $change,
            'OUT' => $current - $change,
            'EXPIRED', 'BROKEN', 'OTHERS' => $current - $change,
            default => throw new \Exception('Invalid transaction type'),
        };
    }

    public function getTransaction(string $id): ?Transaction
    {
        return Transaction::with(['items.product', 'createdBy'])->find($id);
    }

    public function getAllTransactions(int $perPage = 10)
    {
        return Transaction::with(['items.product', 'createdBy'])
            ->latest()
            ->paginate($perPage);
    }
} 