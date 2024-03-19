<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'customer' => $this->customer->name,
            'product' => $this->product->name,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'total_amount' => $this->total_amount
        ];
    }
}
