<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required','string'],
            'description' => ['required','string'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'image' => ['required','string'],
            'status' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
