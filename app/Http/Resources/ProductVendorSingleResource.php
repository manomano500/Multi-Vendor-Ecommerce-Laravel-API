<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Product */
class ProductVendorSingleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'quantity' => $this->quantity,
            'category_id' => $this->category_id,
            'store_id' => $this->store_id,
            'price' => $this->price,
            'status' => $this->status,
//            'variations' => $this->variations->map(function ($variation) {
//                return [
//                    'id' => $variation->id,
//                    'attribute_name' => $variation->attribute->name,
//                    'value' => $variation->value,
//                ];
//            }),
            ];

    }
}
