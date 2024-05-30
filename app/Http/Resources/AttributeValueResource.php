<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Variant */
class AttributeValueResource extends JsonResource
{
    public function toArray(Request $request): array
    {
//        protected $table = 'attribute_values_view';
        return [

            "variation_id" => $this->variation_id,


        ];
    }
}
