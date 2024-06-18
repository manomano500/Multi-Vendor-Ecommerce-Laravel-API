<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminStoreResource extends JsonResource
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
            'image' => $this->image,
            'category' => $this->category->name,
            'status' => $this->status,
            'user' => $this->user->name,
            'address' => $this->address,
            'products' => $this->products->count(),
            'orders' => $this->orders->count(),
        ];
    }
}
