<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    protected $errorBag = 'daftar';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],   // minimal 6 karakter, sama seperti versi lama
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'name.max' => 'Nama maksimal 100 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar. Silakan masuk.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 6 karakter.',
        ];
    }
}
