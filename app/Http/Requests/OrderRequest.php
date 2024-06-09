<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'city' => 'required|string|max:255',
            'shipping_address' => 'required|string|max:255',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.variations' => 'required|array',
            'products.*.variations.*.attribute_id' => 'required|exists:attributes,id',
            'products.*.variations.*.variation_id' => 'required|exists:variations,id',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
