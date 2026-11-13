# North Sumatra Discovery

Portal untuk mengenal **kuliner, budaya, dan tradisi Sumatera Utara**, dari sisi kota sampai sisi kampung. Destinasi hadir sebagai pelengkap ("Tempat Singgah").

Demo: https://north-sumatra-discovery.site.je/

## Fitur
- **Kuliner, Budaya & Tradisi, dan Tempat Singgah**: daftar, detail, dan filter per daerah.
- **Pencarian** lintas ketiganya.
- **Favorit dan ulasan** untuk pengguna yang sudah masuk.
- **Cerita pengunjung**: unggah foto dan cerita (foto otomatis diperkecil ke WebP).
- **Panel admin** untuk mengelola konten.
- **Tema terang dan gelap**, dengan palet Ulos Electric (lihat `PANDUAN-WARNA-ULOS-ELECTRIC.md`).
- Hero **kota ↔ kampung** yang bisa digeser, video sorotan dari URL online atau YouTube.

## Teknologi
Laravel 13, PHP 8.3+, MySQL/MariaDB, Bootstrap 5.3, Font Awesome 6, JavaScript biasa (tanpa build step).

## Menjalankan di komputer sendiri
Langkah lengkap untuk pemula ada di [`README-INSTALASI.md`](README-INSTALASI.md). Ringkasnya:

```bash
composer install
cp .env.example .env        # Windows: copy .env.example .env
php artisan key:generate
# buat database kosong, lalu isi DB_* dan SEED_*_PASSWORD di .env
php artisan migrate --seed
php artisan serve
```

Video, foto latar, dan tautan sosial diatur lewat `.env` (lihat bagian "Media & tautan" di `.env.example`).

## Struktur singkat
| Folder | Isi |
|---|---|
| `app/` | Controller, model, helper |
| `resources/views/` | Tampilan Blade (`beranda.blade.php`, komponen, layout) |
| `public/assets/` | CSS dan JS (palet ada di `style.css` dan `dark.css`) |
| `database/seeders/` | Data awal: daerah, kuliner, budaya, destinasi |
| `config/video.php` | Pengaturan video dan media beranda |

Catatan identitas dan perubahan desain: [`CATATAN-IDENTITAS.md`](CATATAN-IDENTITAS.md).

## Keamanan
File `.env` (berisi sandi database dan kunci aplikasi) **tidak** ikut di repositori. Jangan pernah meng-commit file itu.

## Pembuat
Bit2Bit
