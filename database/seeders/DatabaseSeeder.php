<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     *
     * Urutan PENTING: daerah & kategori dulu, karena kuliner/budaya/destinasi
     * mencari id-nya lewat slug.
     */
    public function run(): void
    {
        $this->call([
            DaerahSeeder::class,
            KategoriSeeder::class,
            UserSeeder::class,
            KulinerSeeder::class,
            BudayaSeeder::class,
            DestinasiSeeder::class,
            BeritaSeeder::class,
        ]);
    }
}
