<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    // Galat validasi disimpan di "bag" bernama 'masuk' -> di Blade: $errors->masuk
    // (halaman /masuk punya 2 form: Masuk & Daftar, jadi galatnya dipisah)
    protected $errorBag = 'masuk';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:150'],
            'password' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
        ];
    }
}
