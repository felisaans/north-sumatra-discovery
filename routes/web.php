<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ResourceController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\BudayaController;
use App\Http\Controllers\DestinasiController;
use App\Http\Controllers\FavoritController;
use App\Http\Controllers\KulinerController;
use App\Http\Controllers\PencarianController;
use App\Http\Controllers\PengalamanController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\UlasanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes North Sumatra Discovery
|--------------------------------------------------------------------------
| Struktur mengikuti bagian 6 dokumen audit.
| Lihat daftar lengkap dengan:  php artisan route:list
*/

/* ---------- PUBLIK (siapa saja) ---------- */
Route::get('/', [BerandaController::class, 'index'])->name('beranda');

// Pencarian menyeluruh: kuliner + budaya + destinasi sekaligus (form di hero Beranda)
Route::get('/cari', [PencarianController::class, 'index'])->name('cari.index');

Route::get('/kuliner', [KulinerController::class, 'index'])->name('kuliner.index');
Route::get('/kuliner/{kuliner:slug}', [KulinerController::class, 'show'])->name('kuliner.show');

Route::get('/budaya', [BudayaController::class, 'index'])->name('budaya.index');
Route::get('/destinasi', [DestinasiController::class, 'index'])->name('destinasi.index');

// Untuk mesin pencari
Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('robots');
Route::get('/sitemap.xml', [SitemapController::class, 'sitemap'])->name('sitemap');

/* ---------- TAMU SAJA (yang sudah login dialihkan ke Beranda) ---------- */
Route::middleware('guest')->group(function () {
    Route::get('/masuk', [AuthController::class, 'form'])->name('masuk');
    Route::post('/masuk', [AuthController::class, 'login'])->name('masuk.proses');
    Route::post('/daftar', [AuthController::class, 'register'])->middleware('throttle:10,1')->name('daftar');
});

/* ---------- WAJIB LOGIN ---------- */
Route::middleware('auth')->group(function () {
    Route::get('/favorit', [FavoritController::class, 'index'])->name('favorit.index');
    Route::post('/keluar', [AuthController::class, 'logout'])->name('keluar');
    Route::post('/kuliner/{kuliner:slug}/ulasan', [UlasanController::class, 'store'])->name('ulasan.store');
    Route::post('/kuliner/{kuliner:slug}/favorit', [FavoritController::class, 'toggle'])->name('favorit.toggle');

    // Membagikan pengalaman WAJIB login; throttle = maks 10 kiriman per menit per pengguna
    Route::post('/pengalaman', [PengalamanController::class, 'store'])
        ->middleware('throttle:10,1')
        ->name('pengalaman.store');
    // Suka / batal suka pengalaman di Beranda
    Route::post('/pengalaman/{pengalaman}/suka', [PengalamanController::class, 'suka'])
        ->middleware('throttle:60,1')
        ->name('pengalaman.suka');
});

/* ---------- ADMIN (login + peran admin) ---------- */
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Satu resource per modul di config/admin.php. Tanpa halaman create/edit/show:
    // form tambah & ubah berupa modal di halaman index (dikerjakan di tahap frontend).
    foreach (config('admin.modul') as $kunci => $modul) {
        $aksi = ($modul['hanya_moderasi'] ?? false)
            ? ['index', 'update', 'destroy']            // Pengalaman: tanpa Tambah
            : ['index', 'store', 'update', 'destroy'];

        Route::resource($kunci, ResourceController::class)
            ->only($aksi)
            ->parameters([$kunci => 'id']);
    }
});
