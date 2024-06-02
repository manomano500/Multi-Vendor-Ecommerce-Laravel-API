<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'thumb_image' => 'required|string',
//            'store_id' => 'required|integer',
            'category_id' => 'required|integer|exists:categories,id',
            'quantity' => 'required|integer', // 'quantity' is added to the fillable array
            'price' => 'required|numeric',
            'status' => 'required|boolean',
            'variants' => 'required|array',
            'variant.*.attribute' => 'required|integer|exists:attributes,id',
            'variant.*.values' => 'required|array',
            'variant.*.values.*.value' => 'required|string|exists:attribute_values,value_id',
        ];


    }


public function validate()
{
    return $this->validated();
}

    public function authorize(): bool
    {
        return true;
    }


}
