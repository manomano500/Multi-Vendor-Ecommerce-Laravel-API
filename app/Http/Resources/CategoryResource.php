<?php

namespace App\Http\Resources;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Category */
class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
//            'created_at' => $this->created_at,
//            'updated_at' => $this->updated_at,
            'id' => $this->id,
            'name' => $this->name,
//            'children' => $this->children,
            'category_id' => $this->category_id,

        ];
    }
}
