<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Auth middleware handles authentication
    }

    public function rules(): array
    {
        $maxKb = config('feed.max_media_mb', 100) * 1024;
        $imageMimes = implode(',', config('feed.allowed_image_mimes', ['jpg', 'jpeg', 'png', 'gif', 'webp']));
        $videoMimes = implode(',', config('feed.allowed_video_mimes', ['mp4', 'mov', 'avi', 'webm', 'mkv', '3gp']));
        $allMimes = $imageMimes . ',' . $videoMimes;

        return [
            'body'          => ['nullable', 'string', 'max:1000'],
            'mediaFiles'    => ['nullable', 'array', 'max:' . config('feed.max_media_files', 10)],
            'mediaFiles.*'  => ['file', "mimes:{$allMimes}", "max:{$maxKb}"],
            'group_id'      => ['nullable', 'integer', 'exists:groups,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'body.max'              => 'Teks postingan maksimal 1000 karakter.',
            'mediaFiles.*.mimes'    => 'Format file tidak didukung. Gunakan JPG, PNG, GIF, WEBP, MP4, MOV, WEBM, atau MKV.',
            'mediaFiles.*.max'      => 'Ukuran file maksimal ' . config('feed.max_media_mb', 100) . 'MB.',
            'mediaFiles.max'        => 'Maksimal ' . config('feed.max_media_files', 10) . ' file per postingan.',
        ];
    }
}
