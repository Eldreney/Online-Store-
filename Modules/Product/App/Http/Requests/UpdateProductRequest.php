<?php

namespace Modules\Product\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('product');

        return [
            'title' => 'required|string|max:255',
            'slug'  => 'required|string|max:255|unique:products,slug,' . $id,
            'description' => 'nullable|string',

            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',

            'category_id' => 'required|integer|exists:categories,id',
            'sub_category_id' => 'nullable|integer|exists:sub_categories,id',
            'brand_id' => 'nullable|integer|exists:brands,id',

            'is_featured' => 'required|in:Yes,No',

            'sku' => 'required|string|max:100',
            'barcode' => 'nullable|string|max:100',

            'track_qty' => 'required|in:Yes,No',
            'qty' => 'nullable|integer|min:0',

            'status' => 'required|in:0,1',
        ];
    }
}
