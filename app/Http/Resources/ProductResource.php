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
            'category_id' => CategoryParentResource::make($this->category),
            'price' => $this->price,
            'status' => $this->status,
            'variations' => VariationResource::collection($this->variations)

        ];
    }
}
