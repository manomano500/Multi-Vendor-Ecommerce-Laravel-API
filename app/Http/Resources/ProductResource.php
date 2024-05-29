<?php

namespace App\Http\Resources;

use App\Models\ProductValue;
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
            'thumb_image' => $this->thumb_image,
            'category_id' => $this->category_id,
            'status' => $this->status,
            'store_id' => $this->store_id,
            'attribute_values' => $this->productattributeValues->map(function (ProductValue $attributeValue) {
                return [
                    'id' => $attributeValue->id,
                    'attribute' => $this->attributeValues,
//                    'value' => $attributeValue->value->name,
                ];
            }),


        ];
    }
}
