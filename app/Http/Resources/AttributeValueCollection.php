<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

/** @see \App\Models\AttributeValuesView */
class AttributeValueCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return $this->collection->groupBy('attribute_name')->map(function ($group, $key) {
            return [
                'attribute_name' => $key,
                'variations' => $group->map(function ($item) {
                    return [
                        'variation_id' => $item->variation_id,
                        'value' => $item->value,
                    ];
                }),
            ];
        })->values()->all();

    }
}
