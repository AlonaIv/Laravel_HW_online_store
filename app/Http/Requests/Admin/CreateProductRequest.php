<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CreateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can(config('permission.access.products.publish'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => ['required', 'string', 'min:2', 'max:255', 'unique:products'],
            'description' => ['nullable', 'string'],
            'SKU' => ['required', 'string', 'min:1', 'max:35', 'unique:products'],
            'price' => ['required', 'numeric', 'min:1'],
            'discount' => ['required', 'numeric', 'min:0', 'max:99'],
            'quantity' => ['required', 'numeric', 'min:0'],
            'categories.*' => ['required', 'numeric', 'exists:App\Models\Category,id'],
            'thumbnail' => ['required', 'image:jpeg,png'],
            'images.*' => ['image:jpeg,png']
        ];
    }
}
