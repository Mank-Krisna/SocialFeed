<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGroupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:100', 'unique:groups,name'],
            'description' => ['nullable', 'string', 'max:500'],
            'type'        => ['required', 'in:public,private'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama grup tidak boleh kosong.',
            'name.max'      => 'Nama grup maksimal 100 karakter.',
            'name.unique'   => 'Nama grup sudah digunakan.',
            'type.in'       => 'Tipe grup harus publik atau privat.',
        ];
    }
}
