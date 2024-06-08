<?php

namespace App\Http\Resources\Categories;

use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryParentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            "name"=>$this->name ,

            'products' => ProductResource::collection($this->whenLoaded('products')),



        ];
    }
}
