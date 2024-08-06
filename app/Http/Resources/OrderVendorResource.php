<?php

namespace App\Http\Resources;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

/** @mixin Order */


class OrderVendorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        Log::info($request);

        return [
            'id' => $this->id,
            'user_name' => $this->user->name,

'payment_method' => $this->payment_method,
            'payment_status' => $this->payment_status,
            'status' => $this->status,
            'shipping_address' => $this->shipping_address,
            'created_at' => $this->created_at->diffForHumans(),
            'order_total'=> $this->products->sum(function($product){
                return $product->pivot->price * $product->pivot->quantity;
            }),
            'products'=>$this->products->map(function($product){
                return [
                    'product_id'=>$product->id,
                    'name'=>$product->name,
                    'price' => $product->pivot->price,
                    'quantity'=>$product->pivot->quantity,
                ];
            }),

           ];

    }
    protected function calculateOrderTotalForStore($storeId)
    {
        // Ensure products are loaded
        return $this->products->filter(function ($product) use ($storeId) {
            return $product->store_id == $storeId;
        })->sum('pivot.price');
    }
}
