<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

/** @see \App\Models\Product */
class  ProductVendorAllCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
             ProductVendorAllResource::collection($this->collection),

        ];
    }
}
