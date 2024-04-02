<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'name' => $this->name,
            'thumbnailUrl' => $this->thumbnail_url,
            'categoryName' => $this->category->name,
            'stock' => $this->stock->quantity,
            'price' => $this->price,
            'salesCount' => $this->salesCount,
            'revenue' => $this->revenue,
            'lastUpdate' => $this->last_update,
        ];
    }
}
