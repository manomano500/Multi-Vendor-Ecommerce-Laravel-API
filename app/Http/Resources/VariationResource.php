<?php

namespace App\Http\Resources;

use App\Models\Variation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Variation */
class VariationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'value' => $this->value,
            'attribute' => $this->attribute_id,





        ];
    }
}
// Compare this snippet from app/Http/Resources/VariationResource.php:
