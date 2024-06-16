<?php

namespace App\Http\Resources;

use Carbon\Carbon;
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

            'created_at' => $this->created_at ? Carbon::parse($this->created_at)->format('d-n-Y H:i:s') : null,

            'products'=>$this->products->map(function($product){
                return [
                    'product_id'=>$product->id,
                    'name'=>$product->name,

                    'price' => $product->pivot->price,
                    'quantity'=>$product->pivot->quantity,
                    'store_id'=>$product->store->name,
                ];
}),
        ];
    }
}
