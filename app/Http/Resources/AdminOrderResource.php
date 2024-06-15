<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminOrderResource extends JsonResource
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
            'user'=> $this->user,
            'orderProducts'=>$this->products->map(function($product){
                return [
                    'product_id'=>$product->id,
                    'name'=>$product->name,
                    'price' => $product->pivot->price,
                    'quantity'=>$product->pivot->quantity,
                ];
            }),
            'order_status' => $this->status,
            'shipping_address' => $this->shipping_address,
            'created_at' => $this->created_at->diffForHumans(),

        ];
    }
}
