# North Sumatra Discovery: menjalankan di laptop sendiri

Folder ini adalah **source lengkap** (struktur Laravel standar) yang disamakan dengan versi yang sudah di-hosting.
Yang TIDAK ikut: `.env` hosting (berisi sandi database), `pasang.php` (installer khusus InfinityFree), dan folder `vendor/` (dibuat ulang oleh Composer).

## Cara cepat (Windows + Laragon)
1. Pasang **Laragon Full** (laragon.org), lalu klik **Start All**. Pastikan PHP **8.3 atau lebih baru** (klik kanan Laragon → PHP → Version).
2. Ekstrak folder ini ke `C:\laragon\www\`, buka **Terminal** Laragon, lalu: `cd north-sumatra-discovery-source`
3. `composer install`
4. `copy .env.example .env` lalu `php artisan key:generate`
5. Buat database kosong bernama `north_sumatra_discovery` (tombol **Database** di Laragon, lalu klik kanan → Create new → Database).
6. Buka `.env`: isi `DB_USERNAME=root`, kosongkan `DB_PASSWORD`, isi `SEED_ADMIN_PASSWORD` dan `SEED_USER_PASSWORD` (sandi bebas). Gunakan `DB_CONNECTION=mysql` bila Laragon-mu memakai MySQL.
7. `php artisan migrate --seed`
8. `php artisan serve`, lalu buka http://127.0.0.1:8000

Catatan:
- Video, foto latar, dan tautan sosial di `.env.example` disalin dari versi hosting (semuanya URL publik), jadi tampilan lokal sama dengan yang online.
- Foto kiriman pengunjung disimpan di `public/storage/pengalaman/` (tanpa perlu `storage:link`). Foto yang sudah ada di hosting tidak ikut karena terikat ke database hosting.
- Database lokal berisi data awal dari seeder, bukan data yang sudah masuk di hosting. Untuk memindahkan data hosting, ekspor dari phpMyAdmin InfinityFree lalu impor ke database lokal (hanya bila perlu).

---

# North Sumatra Discovery — Panduan Instalasi

Portal kuliner, budaya & tradisi Sumatera Utara.
Demo online: https://north-sumatra-discovery.site.je/

## Isi paket

| Item | Keterangan |
|---|---|
| `north-sumatra-discovery-source.zip` | Source code Laravel (tanpa `vendor/` dan `.env`) |
| `north_sumatra_discovery.sql` | Database lengkap (struktur + data), MySQL/MariaDB |
| `README-INSTALASI.md` | Panduan ini |

## Kebutuhan

- PHP **8.3** atau lebih baru (ekstensi: mbstring, pdo_mysql, openssl, fileinfo, gd)
- Composer 2
- MySQL 8 / MariaDB 10.6+ (XAMPP, Laragon, atau Laravel Herd)

## Langkah A — Buat database kosong

> Paket ini tidak menyertakan file `.sql`. Isi database dibuat lewat seeder (lihat bagian "Migrasi + seeder" di bawah).

(Bagian impor `.sql` di bawah hanya berlaku bila kamu punya file `.sql` sendiri.)

1. Buat database kosong, misalnya `north_sumatra_discovery` (lewat phpMyAdmin, atau `CREATE DATABASE north_sumatra_discovery CHARACTER SET utf8mb4;`).
2. Impor `north_sumatra_discovery.sql` ke database itu (phpMyAdmin → pilih database → tab **Import** → pilih file → **Go**).

## Langkah B — Pasang project

```bash
# 1. Ekstrak source code, lalu masuk ke foldernya
cd north-sumatra-discovery-source

# 2. Pasang dependensi PHP
composer install

# 3. Siapkan konfigurasi
cp .env.example .env          # Windows (CMD): copy .env.example .env
php artisan key:generate
```

Buka `.env`, lalu isi `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` sesuai database lokal.

```bash
# 4. Jalankan
php artisan serve             # buka http://127.0.0.1:8000
```

Catatan: **tidak perlu** menjalankan `php artisan storage:link`. Foto kiriman pengunjung disimpan langsung di `public/storage/` (diatur di `config/filesystems.php`), jadi pastikan folder itu bisa ditulis.

Kalau memakai Laravel Herd, cukup letakkan folder di direktori Herd, lalu buka `http://north-sumatra-discovery.test`.

## Migrasi + seeder (cara yang dipakai paket ini)

Setelah Langkah B nomor 1–3 dan `.env` terisi (termasuk blok `SEED_*`):

```bash
php artisan migrate --seed
```

Perintah ini membuat tabel dari `database/migrations` dan mengisi data awal (daerah, kategori, kuliner, budaya, destinasi, berita, serta akun admin dan pengguna demo) dari `database/seeders`.
Catatan: cara ini tidak membawa ulasan, favorit, atau pengalaman yang dibuat pengunjung di situs online.

## Akun demo

| Peran | Email | Sandi |
|---|---|---|
| Admin (`/admin`) | admin@nsdiscovery.test | admin1234 |
| Pengguna | user@nsdiscovery.test | user1234 |

## Struktur singkat

```
public/                 # Pintu masuk web: index.php, assets (css/js/img), favicon, storage (foto unggahan)
app/Http/Controllers/   # Beranda, Kuliner, Budaya, Destinasi, Pencarian, Favorit, Ulasan, Pengalaman, Auth, Admin
resources/views/        # Tampilan Blade (layouts, partials, components, halaman)
routes/web.php          # Seluruh rute
database/migrations/    # Skema tabel
database/seeders/       # Data awal
config/admin.php        # Definisi modul dashboard admin
```
