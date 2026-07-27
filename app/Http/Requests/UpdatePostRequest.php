<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Policy handles authorization
    }

    public function rules(): array
    {
        return [
            'body' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'body.max' => 'Teks postingan maksimal 1000 karakter.',
        ];
    }
}
