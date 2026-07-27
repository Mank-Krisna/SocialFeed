<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendFriendRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'receiver_id' => [
                'required',
                'integer',
                'exists:users,id',
                'different:' . auth()->id(), // Can't send to self
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'receiver_id.required'  => 'ID pengguna tujuan diperlukan.',
            'receiver_id.exists'    => 'Pengguna tidak ditemukan.',
            'receiver_id.different' => 'Tidak dapat mengirim permintaan pertemanan ke diri sendiri.',
        ];
    }
}
