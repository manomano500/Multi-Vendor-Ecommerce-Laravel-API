<?php

namespace App\Http\Resources\Product;

use App\Http\Resources\CategoryResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Product */
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
            "attributes"=> $this->variations->groupBy('attribute_id')

        ];
    }
}
