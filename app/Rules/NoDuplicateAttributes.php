<?php

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\ValidationRule;

class NoDuplicateAttributes implements ValidationRule
{
    public function passes($attribute, $value)
    {
        $attributeIds = collect($value)->pluck('attribute_id');
        return $attributeIds->count() === $attributeIds->unique()->count();
    }

    public function message()
    {
        return 'Duplicate attributes are not allowed.';
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$this->passes($attribute, $value)) {
            $fail($this->message());
        }
    }
}
