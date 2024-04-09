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
            'description' => $this->whenAppended('description'),
            'thumbnailUrl' => $this->thumbnail_url,
            'category' => $this->whenLoaded('category'),
            'stock' => $this->stock->quantity,
            'price' => $this->price,
            'salesCount' => $this->salesCount,
            'revenue' => $this->revenue,
            'lastUpdate' => $this->last_update,
            'images' => $this->whenLoaded('images'),
        ];
    }
}
