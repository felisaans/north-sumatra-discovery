<?php

namespace Database\Seeders;

use App\Models\Daerah;
use Illuminate\Database\Seeder;

class DaerahSeeder extends Seeder
{
    public function run(): void
    {
        // Data dari schema.sql lama. 'semua' = baris khusus "Seluruh Wilayah Sumatera Utara"
        $data = [
            ['Kota Medan', 'kota-medan'],
            ['Deli Serdang', 'deli-serdang'],
            ['Karo', 'karo'],
            ['Samosir', 'samosir'],
            ['Toba', 'toba'],
            ['Tapanuli Utara', 'tapanuli-utara'],
            ['Nias Selatan', 'nias-selatan'],
            ['Mandailing Natal', 'mandailing-natal'],
            ['Dairi', 'dairi'],
            ['Langkat', 'langkat'],
            ['Seluruh Wilayah Sumatera Utara', 'semua'],
        ];

        // updateOrCreate = aman dijalankan berulang (tidak membuat data ganda)
        foreach ($data as [$nama, $slug]) {
            Daerah::updateOrCreate(['slug' => $slug], ['nama_daerah' => $nama]);
        }
    }
}
