<?php

namespace App\Http\Resources\Product;

use App\Http\Resources\CategoryResource;
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
//            'store_id' => $this->store_id,
            'category_id' => CategoryResource::make($this->category),
            'price' => $this->price,
            'status' => $this->status,
            "attributes"=> $this->variations->map(function ($variations){
                return [
                    'attribute_id' => $variations->attribute->id,
                    'attribute_name' => $variations->attribute->name,
                    'variation_id' => $variations->name,
                ];
            }),
        ];
    }
}
