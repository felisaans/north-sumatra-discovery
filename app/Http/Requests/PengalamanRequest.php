<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PengalamanRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Wajib login (route juga sudah dilindungi middleware auth); nama diambil dari akun, bukan dari form
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'daerah_id' => ['required', 'integer', 'exists:daerah,id'],
            'destinasi' => ['required', 'string', 'max:100'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'cerita' => ['required', 'string', 'max:2000'],
            // Validasi di SERVER wajib (versi lama hanya di browser): JPG/PNG/WEBP, maks 5 MB
            'foto' => ['required', 'file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'daerah_id.required' => 'Pilih daerah.',
            'daerah_id.exists' => 'Daerah tidak valid.',
            'destinasi.required' => 'Destinasi wajib diisi.',
            'rating.required' => 'Pilih rating.',
            'rating.between' => 'Rating harus antara 1 sampai 5.',
            'cerita.required' => 'Ceritakan pengalamanmu.',
            'cerita.max' => 'Cerita maksimal 2000 karakter.',
            'foto.required' => 'Foto wajib diunggah.',
            'foto.mimes' => 'File harus berupa gambar (JPG, PNG, WEBP).',
            'foto.max' => 'Ukuran file maksimal 5MB.',
            'foto.uploaded' => 'Unggahan gagal. Pastikan ukuran file maksimal 5MB.',
        ];
    }
}
