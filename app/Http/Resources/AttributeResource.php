<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Attribute */
class AttributeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [

            'id' => $this->id,
            'name' => $this->name,
//            'variations' => $this->variations->map(function ($variation) {
//                return [
//                    'id' => $variation->id,
//                    'value' => $variation->value,
//                ];
//            }),
        ];
    }
}
