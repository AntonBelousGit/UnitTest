<?php

namespace App;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|min:6',
            'price' => 'required'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
