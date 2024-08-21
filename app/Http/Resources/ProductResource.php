<?php

namespace App\Http\Resources;

use App\Http\Resources\Categories\CategoryParentResource;
use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Product */
class ProductResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'category_id' => $this->category->name,
            'price' => $this->price,
            'status' => $this->status,
            'quantity' => $this->quantity,
            'images' => $this->images->map(function ($image) {
//                return $image->image;
                return $image->getImageUrlAttribute() ;
                //TODO:  this line return the user uploaded images
            }),
//        'images'=>$this->images()->get(['id','image']),
            'variations' =>$this->variations->map(function ($variation) {
                return [
                    'attribute' => $variation->attribute->name,
                    'variation_id' => $variation->id,
                    'value' => $variation->value,
                ];
            })


            ];

    }
}
