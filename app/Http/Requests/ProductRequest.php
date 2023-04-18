<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property  int category_id
 * @property  string name
 * @property  string brand
 * @property  string sku
 * @property  string price
 * @property  bool is_available
 */
class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'category_id' => 'required|numeric',
            'name' => 'required|string|min:5| max:100',
            'brand' => 'string|nullable|max:65',
            'sku' => 'string|nullable|max:65',
            'price' => 'required|numeric',
            'is_available' => 'boolean',
        ];
    }
}
