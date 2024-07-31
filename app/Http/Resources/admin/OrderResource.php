<?php
namespace App\Http\Resources\admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'order_id' => $this->id,
            'order_total' => $this->order_total,
            'order_status' => $this->status,
            'shipping_address' => $this->shipping_address,
            'created_at' => $this->created_at ? Carbon::parse($this->created_at)->format('d-n-Y H:i') : null,
            'products' => $this->products->map(function($product) {
                return [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'store_name' =>  $product->store->name,
                    'price' => $product->pivot->price,
                    'quantity' => $product->pivot->quantity,
                ];
            }),

        ];
        // Group products by store_id
//        $groupedProducts = $this->orderProducts->groupBy('store_id')->map(function ($products, $storeId) {
//            return [
//                'store_id' => $storeId,
//
//                'products' => $products->map(function ($orderProduct) {
//                    return [
//                        'id' => $orderProduct->id,
//                        'product_id' => $orderProduct->product_id,
//                        'quantity' => $orderProduct->quantity,
//                        'price' => $orderProduct->price,
//                        'status' => $orderProduct->status,
//                    ];
//                }),
//            ];
//        })->values();
//
//        return [
//            'id' => $this->id,
//            'user' => [
//                'id' => $this->user->id,
//                'name' => $this->user->name,
//                'email' => $this->user->email,
//            'phone' => $this->user->phone,
//
//            ],
//            'status' => $this->status,
//            'address' => $this->address,
//            'total' => $this->order_total,
//            'created_at' => $this->created_att,
//
//            'products' => $groupedProducts,
//        ];
    }
}
