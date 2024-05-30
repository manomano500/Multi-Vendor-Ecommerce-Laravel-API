<?php

namespace App\Http\Resources\Product;

use App\Http\Resources\CategoryResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Product */
class ProductVendorAllResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'thumb_image' => $this->thumb_image,
            'store_id' => $this->store_id,
            'category' => CategoryResource::make($this->category),
            'price' => $this->price,
            'status' => $this->status,

        ];
    }
}
