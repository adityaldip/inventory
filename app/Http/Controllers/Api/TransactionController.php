<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TransactionController extends Controller
{
    public function __construct(private TransactionService $transactionService)
    {
    }

    public function index(Request $request)
    {
        $transactions = $this->transactionService->getAllTransactions($request->per_page ?? 10);
        return TransactionResource::collection($transactions);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => ['required', Rule::in(['IN', 'OUT', 'EXPIRED', 'BROKEN', 'OTHERS'])],
            'customer_email' => 'nullable|email',
            'customer_name' => 'nullable|string',
            'supplier_name' => 'nullable|string',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $transaction = $this->transactionService->createTransaction($validated);
        return new TransactionResource($transaction);
    }

    public function show(string $id)
    {
        $transaction = $this->transactionService->getTransaction($id);
        return new TransactionResource($transaction);
    }
} 