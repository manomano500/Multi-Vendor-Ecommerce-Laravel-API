<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

/** @mixin \App\Models\Order */
class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,


            'order_total' => $this->order_total,
            'order_status' => $this->status,
           'shipping_address' => $this->shipping_address,

            'created_at' => $this->created_at->diffForHumans(),

            'products'=>$this->products->map(function($product){
                return [
                    'id'=>$product->id,
                    'name'=>$product->name,
                    'price'=>$product->price,
                    'quantity'=>$product->pivot->quantity,
                    'store_id'=>$product->store_id,
                ];
}),
        ];
    }
}
