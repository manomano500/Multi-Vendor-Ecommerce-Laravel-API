<?php

namespace App\Http\Resources;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Order */
class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'order_total' => $this->order_total,
            'order_status' => $this->status?? null,
            'shipping_address' => $this->user->address ?? null,
           'user_phone' => $this->user->phone ?? null,

             'created_at' => $this->created_at ? Carbon::parse($this->created_at)->format('d-n-Y H:i') : null,
        ];

        if ($this->relationLoaded('products')) {
            $data['products'] = $this->products->map(function($product) {
                return [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_status' => $product->pivot->status,
                    'store_name' => $product->store->name ?? null,
                    'store_id' => $product->store->id ?? null,
                    'price' => $product->pivot->price,
                    'quantity' => $product->pivot->quantity,
                    'variations' => $product->pivot->variations() ?? 'N/A',
                ];
            });
        }

        return $data;
    }
}
