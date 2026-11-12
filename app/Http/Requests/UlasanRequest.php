<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UlasanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;   // sudah dijaga middleware 'auth' di route
    }

    public function rules(): array
    {
        return [
            'rating' => ['required', 'integer', 'between:1,5'],
            'komentar' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'rating.required' => 'Pilih rating 1 sampai 5 bintang.',
            'rating.between' => 'Rating harus antara 1 sampai 5.',
            'komentar.max' => 'Komentar maksimal 1000 karakter.',
        ];
    }
}
