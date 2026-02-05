<?php

namespace Modules\Brand\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBrandRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        // optional auto slug if empty
        if (!$this->filled('slug') && $this->filled('name')) {
            $this->merge([
                'slug' => \Illuminate\Support\Str::slug($this->name),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'name'   => ['required', 'string', 'max:255'],
            'slug'   => ['required', 'string', 'max:255', 'unique:brands,slug'],
            'status' => ['required', 'integer', 'in:0,1'],
        ];
    }
}
