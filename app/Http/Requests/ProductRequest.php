<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isCreate = $this->isMethod('post');

        return [
            'category_id' => ['required', 'exists:categories,id'],
            'subcategory_id' => [
                'required',
                Rule::exists('subcategories', 'id')->where('category_id', $this->category_id),
            ],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'image' => [$isCreate ? 'required' : 'nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'old_price' => ['nullable', 'numeric', 'min:0'],
            'new_price' => ['required', 'numeric', 'min:0'],
            'stock' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'subcategory_id.exists' => 'The selected subcategory does not belong to the selected category.',
        ];
    }
}
