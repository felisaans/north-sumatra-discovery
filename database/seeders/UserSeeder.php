<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Email & sandi diambil dari .env (lihat blok SEED_* di .env.example).
        // Sandi WAJIB diisi di .env (tidak ada sandi cadangan di kode, supaya aman bila repositori dibuka publik).
        $this->buat(
            nama: env('SEED_ADMIN_NAME', 'Admin North Sumatra Discovery'),
            email: env('SEED_ADMIN_EMAIL', 'admin@nsdiscovery.test'),
            sandi: $this->sandiDariEnv('SEED_ADMIN_PASSWORD'),
            peran: User::PERAN_ADMIN,
        );

        $this->buat(
            nama: 'Pengguna Contoh',
            email: env('SEED_USER_EMAIL', 'user@nsdiscovery.test'),
            sandi: $this->sandiDariEnv('SEED_USER_PASSWORD'),
            peran: User::PERAN_USER,
        );
    }

    private function sandiDariEnv(string $kunci): string
    {
        $nilai = (string) env($kunci, '');

        if (strlen($nilai) < 8) {
            throw new \RuntimeException(
                "{$kunci} belum diisi di file .env (minimal 8 karakter). "
                . 'Isi dulu, simpan, lalu jalankan: php artisan db:seed'
            );
        }

        return $nilai;
    }

    private function buat(string $nama, string $email, string $sandi, string $peran): void
    {
        // 'peran' tidak ada di $fillable, jadi diisi dengan forceFill() (khusus seeder)
        $user = User::firstOrNew(['email' => $email]);
        $user->forceFill([
            'name' => $nama,
            'password' => $sandi,          // otomatis di-hash oleh cast 'hashed' di model User
            'peran' => $peran,
            'email_verified_at' => now(),
        ])->save();
    }
}
