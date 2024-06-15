<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class OrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [





            'city' => 'required|string',
            'shipping_address' => 'required|string',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',

//            'products.*.variations' => 'required|array',
//            'products.*.variations.*.variation_id' => 'required|exists:variations,id',

        ];
    }

    public function authorize(): bool
    {
        return true;

    }


/*    protected function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $products = $this->input('products', []);
            foreach ($products as $product) {
                $attributeIds = array_column($product['variations'], 'attribute_id');
                if (count($attributeIds) !== count(array_unique($attributeIds))) {
                    $validator->errors()->add('products.*.variations', 'Duplicate attributes are not allowed.');
                    break;
                }
            }
        });
    }*/
}
