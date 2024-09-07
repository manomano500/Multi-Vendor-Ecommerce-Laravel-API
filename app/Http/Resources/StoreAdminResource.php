<?php

namespace App\Http\Resources;

use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreAdminResource extends JsonResource
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
            'image' => Store::getImageUrl($this->image),
            'category' => $this->category->name ?? 'No Category', // Safely return the category name or a default value
            'status' => $this->status,
            'user' => $this->user_id            ,
            'address' => $this->address,
            'products' => $this->products->count(),
            'orders' => $this->orderCount,
        ];
    }
}
