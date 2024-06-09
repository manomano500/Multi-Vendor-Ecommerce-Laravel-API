<?php

namespace App\Http\Resources;

use App\Http\Resources\Categories\CategoryParentResource;
use App\Models\ProductVariation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Product */
class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'category_id' => $this->category_id,
            'price' => $this->price,
            'status' => $this->status,
            'quantity' => $this->quantity,
            'variations' => VariationResource::collection($this->whenLoaded('variations')),

        ];
    }
}
