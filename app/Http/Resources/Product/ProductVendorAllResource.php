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
            'description' => $this->description,
//            'store_id' => $this->store->name,
            'image' => $this->images()->first()?->getImageUrlAttribute(), // Use accessor to get full URLs

            'category' => $this->category->name,
            'quantity' => $this->quantity,


            'price' => $this->price,
            'status' => $this->status,


        ];


    }

}
