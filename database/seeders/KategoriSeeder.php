<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['Makanan Berat', 'makanan-berat', 'kuliner'],
            ['Minuman', 'minuman', 'kuliner'],
            ['Kue Tradisional', 'kue-tradisional', 'kuliner'],
            ['Tari Tradisional', 'tari', 'budaya'],
            ['Musik Tradisional', 'musik', 'budaya'],
            ['Tenun & Kerajinan', 'kerajinan', 'budaya'],
            ['Arsitektur & Rumah Adat', 'arsitektur', 'budaya'],
            ['Upacara & Tradisi', 'upacara', 'budaya'],
            ['Wisata Sejarah & Religi', 'sejarah-religi', 'destinasi'],
            ['Wisata Sejarah & Edukasi', 'sejarah-edukasi', 'destinasi'],
            ['Wisata Pantai', 'pantai', 'destinasi'],
            ['Wisata Alam', 'alam', 'destinasi'],
        ];

        // slug hanya unik di dalam satu tipe, jadi kunci pencariannya (tipe + slug)
        foreach ($data as [$nama, $slug, $tipe]) {
            Kategori::updateOrCreate(
                ['tipe' => $tipe, 'slug' => $slug],
                ['nama_kategori' => $nama]
            );
        }
    }
}
