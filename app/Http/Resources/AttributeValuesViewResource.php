<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\AttributeValuesView */
class AttributeValuesViewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'variation_id' => $this->variation_id,
            'attribute_name' => $this->attribute_name,
            'value' => $this->value,
        ];
    }
}
