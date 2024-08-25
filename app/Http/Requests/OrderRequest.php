<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class OrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [

                'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id,status,active',
            'products.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|string|in:Adfali,Sadad,localBankCards,pay_on_deliver,mpgs',
            'mobile_number' => 'required_if:payment_method,Adfali,Sadad|nullable|string|max:15',

            'products.*.variations' => 'array',
            'product.*.variations.*.attribute' => '',
            'product.*.variations.*.value' => '',

        ];
    }

    public function authorize(): bool
    {
        return true;

    }



}
