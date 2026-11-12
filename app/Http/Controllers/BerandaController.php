<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Budaya;
use App\Models\Daerah;
use App\Models\Kuliner;
use App\Models\Pengalaman;
use Illuminate\View\View;

class BerandaController extends Controller
{
    public function index(): View
    {
        return view('beranda', [
            // angka di hero dihitung dari database (dulu tulisan tetap "100+")
            'jumlahKuliner' => Kuliner::count(),
            'jumlahBudaya' => Budaya::count(),

            // "Terbaru" = urut created_at turun (id sebagai penentu bila waktunya sama)
            'kulinerPopuler' => Kuliner::with(['daerah', 'kategori'])
                ->denganRating()->latest()->latest('id')->limit(4)->get(),

            'budayaPilihan' => Budaya::with(['daerah', 'kategori'])
                ->latest()->latest('id')->limit(3)->get(),

            // 8 terbaru: dipakai marquee berita otomatis (dulu 3 kartu statis)
            'berita' => Berita::orderByDesc('tanggal')->orderByDesc('id')->limit(8)->get(),

            // chip filter daerah (tanpa "Seluruh Wilayah Sumatera Utara") + isi dropdown form pengalaman
            'daerahChip' => Daerah::untukChip()->get(),
            'daftarDaerah' => Daerah::orderBy('nama_daerah')->get(),

            // jumlah suka + (bila login) apakah pengguna ini sudah menyukai; dipakai tombol suka di slide
            'pengalaman' => Pengalaman::with('daerah')
                ->withCount('suka')
                ->when(auth()->check(), fn ($q) => $q->withExists(['suka as disuka_saya' => fn ($s) => $s->where('user_id', auth()->id())]))
                ->latest()->latest('id')->limit(12)->get(),
        ]);
    }
}
