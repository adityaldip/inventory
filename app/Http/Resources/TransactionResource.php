<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'customer_email' => $this->customer_email,
            'customer_name' => $this->customer_name,
            'supplier_name' => $this->supplier_name,
            'total_amount' => $this->total_amount,
            'notes' => $this->notes,
            'items' => TransactionItemResource::collection($this->whenLoaded('items')),
            'created_by' => new UserResource($this->whenLoaded('createdBy')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
} 