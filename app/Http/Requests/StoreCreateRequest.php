<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [

            'name' => ['required'],
            'description' => ['required'],
            'category_id' => ['required', 'integer'],
            'image' => ['required'],
            'status' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
