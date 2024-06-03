<?php

namespace App\Http\Resources;

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
            'price' => $this->price,
            'quantity' => $this->quantity, // 'quantity' is added to the fillable array
            'category_id' => $this->category_id,
            'status' => $this->status,
            'store_id' => $this->store_id,
            'attributes' => $this->whenLoaded('variations', function () {
                return $this->variations->groupBy('attribute.id')->map(function ($values, $attributeId) {
                    return [
                        'id' => $attributeId,
                        'name' => $values->first()->attribute->name,
                        'values' => VariationResource::collection($values),
                    ];
                })->values();




    })
            ];
    }
}
