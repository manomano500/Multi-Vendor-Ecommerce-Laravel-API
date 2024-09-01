<?php

namespace App\Http\Resources\admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderAdminResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=> $this->id,
            'user_id'=>$this->user->id,
            'order_total'=> $this->order_total,
            'status'=> $this->order_status,
            'payment_status'=> $this->payment_status,
            'payment_method'=> $this->payment_method,
            'created_att'=> $this->created_at->diffForHumans(),

        ];
    }


}
