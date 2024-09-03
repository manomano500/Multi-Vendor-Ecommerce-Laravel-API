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
            'user_id' => $this->user->id,
'user_phone'=>$this->user->phone,
'payment_method' => $this->payment_method,
            'payment_status' => $this->payment_status,
            'status' => $this->status,
            'shipping_address' => $this->shipping_address,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'order_total' => round($this->products->sum(function($product) {
                return $product->pivot->price * $product->pivot->quantity;
            }), 3),
            'products'=>$this->products->map(function($product){
                return [
                    'product_id'=>$product->id,
                    'store_id'=>$product->store_id,
                    'name'=>$product->name,
                    'price' => $product->pivot->price,
                    'quantity'=>$product->pivot->quantity,
                    'status'=>$product->pivot->status,
                    'variations'=>json_decode($product->pivot->variations, true),
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
