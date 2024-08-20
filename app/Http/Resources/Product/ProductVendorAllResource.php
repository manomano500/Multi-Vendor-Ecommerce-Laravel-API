<?php

namespace App\Http\Resources\Product;

use App\Http\Resources\CategoryResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Product */
class ProductVendorAllResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
//            'store_id' => $this->store->name,
//            'image' => $this->images()->first()?->getImageUrlAttribute(), // Use accessor to get full URLs
            'images'=>$this->images()->get(['id','image']),

            'category' => $this->category->name,
            'quantity' => $this->quantity,


            'price' => $this->price,
            'variations' =>$this->variations->map(function ($variation) {
                return [
                    'attribute' => $variation->attribute->name,
                    'variation_id' => $variation->id,
                    'value' => $variation->value,
                ];
            }),
            'status' => $this->status,


        ];


    }

}
