<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransactionItem extends Model
{
    use HasUuid;

    protected $fillable = [
        'quantity',
        'total_amount',
        'quantity_before',
        'quantity_after',
        'product_id',
        'transaction_id',
        'created_by_id'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'quantity' => 'integer',
        'quantity_before' => 'integer',
        'quantity_after' => 'integer',
    ];

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }
} 