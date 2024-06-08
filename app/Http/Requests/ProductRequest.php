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
//            'store_id' => 'required|integer',
            'category_id' => 'required|integer|exists:categories,id',
            'quantity' => 'required|integer', // 'quantity' is added to the fillable array
            'price' => 'required|numeric',
            'variations' => 'required|array',
            'variations.*' => 'required|integer|distinct|exists:variations,id|' ,
            'status' => 'required|enum:active,inactive,out_of_stock',

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
