<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Variation */
class VariationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'=> $this->id,
            'attribute_id'=> $this->attribute_id,
            'attribute_name'=> $this->attribute->name, // 'attribute_name' is added to the array
            'value'=> $this->value,





        ];
    }
}
