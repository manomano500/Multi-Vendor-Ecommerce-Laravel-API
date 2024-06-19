<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Store */
class StoreResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
//            'created_at' => $this->created_at,
//            'updated_at' => $this->updated_at,
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'image' => $this->image,
//            'category' => $this->category->name,
            'status' => $this->status,
//            'status' => $this->status,
            'user' => $this->whenLoaded('user', ['id', 'name', 'email', 'phone']),
            'products' => ProductResource::collection($this->whenLoaded('products')),
//            'products_count' => $this->products()->count(),

        ];
    }
}
