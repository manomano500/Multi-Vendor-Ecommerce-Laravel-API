<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminProductResource extends JsonResource
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
            'description' => $this->description,
            'store' => $this->store->name,
            'category' => $this->category->name,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'status' => $this->status,
            'images' => $this->images->pluck('image'),

            'variations' => $this->variations->pluck('name'),
        ];
    }
}
