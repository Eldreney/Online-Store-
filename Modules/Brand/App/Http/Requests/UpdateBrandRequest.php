<?php

namespace Modules\Brand\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBrandRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if (!$this->filled('slug') && $this->filled('name')) {
            $this->merge([
                'slug' => \Illuminate\Support\Str::slug($this->name),
            ]);
        }
    }

    public function rules(): array
    {
        $brandId = $this->route('brand')?->id ?? $this->route('brand');

        return [
            'name'   => ['required', 'string', 'max:255'],
            'slug'   => [
                'required', 'string', 'max:255',
                Rule::unique('brands', 'slug')->ignore($brandId),
            ],
            'status' => ['required', 'integer', 'in:0,1'],
        ];
    }
}
