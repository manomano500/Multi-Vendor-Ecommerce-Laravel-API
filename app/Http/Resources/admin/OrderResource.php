<?php
namespace App\Http\Resources\admin;

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
        // Group products by store_id
        $groupedProducts = $this->orderProducts->groupBy('store_id')->map(function ($products, $storeId) {
            return [
                'store_id' => $storeId,

                'products' => $products->map(function ($orderProduct) {
                    return [
                        'id' => $orderProduct->id,
                        'product_id' => $orderProduct->product_id,
                        'quantity' => $orderProduct->quantity,
                        'price' => $orderProduct->price,
                        'status' => $orderProduct->status,
                    ];
                }),
            ];
        })->values();

        return [
            'id' => $this->id,
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
            'phone' => $this->user->phone,

            ],
            'status' => $this->status,
            'address' => $this->address,
            'total' => $this->order_total,
            'created_at' => $this->created_att,

            'products' => $groupedProducts,
        ];
    }
}
