<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

/** @see \App\Models\Product */
class ProductCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description,
                    'category' => $product->category->name,
                    'quantity' => $product->quantity,
                    'price' => $product->price,
                    'status' => $product->status,
                    'attributes' => $product->attributeValues->groupBy('attribute.id')->map(function ($values, $attributeId) {
                        return [
                            'id' => $attributeId,
                            'name' => $values->first()->attribute->name,
                            'values' => $values->map(function($value) {
                                return [
                                    'id' => $value->id,
                                    'name' => $value->name,

                                ];
                            })
                        ];
                    })->values()
                ];
            }),
        ];
    }

}
