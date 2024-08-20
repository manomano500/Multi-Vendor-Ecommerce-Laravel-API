<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductIndexResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'category_id' => $this->category->name,
            'price' => $this->price,
            'image' => $this->images->first()?->getImageUrlAttribute(), // Returns only the first image
//        'images'=>$this->images()->get(['id','image']),


        ];    }
}
