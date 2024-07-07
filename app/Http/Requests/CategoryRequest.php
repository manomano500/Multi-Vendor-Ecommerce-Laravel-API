<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => 'required|integer:exists:categories,id',
            'name' => 'required|string|max:255',
            'children' => 'array',
            'children.*.id' => 'required|integer:exists:categories,id',
            'children.*.name' => 'required|string|max:255',

            //
        ];
    }
}
