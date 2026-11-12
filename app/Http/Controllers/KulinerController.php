<?php

namespace App\Http\Controllers;

use App\Models\Daerah;
use App\Models\Kategori;
use App\Models\Kuliner;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KulinerController extends Controller
{
    /** GET /kuliner?q=&daerah=&kategori=   (+ &partial=1 / AJAX -> hanya potongan daftar untuk live search) */
    public function index(Request $request): View
    {
        $q = $this->teks($request, 'q');
        $daerah = $this->teks($request, 'daerah');
        $kategori = $this->teks($request, 'kategori');
        $urut = $this->teks($request, 'urut');

        $kuliner = Kuliner::with(['daerah', 'kategori'])
            ->denganRating()
            ->cari($q)
            ->filter($daerah, $kategori)
            ->urutkan($urut)
            ->get();

        // Live search (live-search.js) meminta potongan HTML saja; tanpa JS halaman lengkap tetap jalan
        if ($request->ajax() || $request->boolean('partial')) {
            return view('kuliner._daftar', ['kuliner' => $kuliner]);
        }

        return view('kuliner.index', [
            'kuliner' => $kuliner,
            'daftarDaerah' => Daerah::orderBy('nama_daerah')->get(),
            'daftarKategori' => Kategori::tipe(Kategori::TIPE_KULINER)->orderBy('nama_kategori')->get(),
            'q' => $q,
            'daerahAktif' => $daerah,
            'kategoriAktif' => $kategori,
            'urutAktif' => $urut,
        ]);
    }

    /** GET /kuliner/{kuliner:slug}  (route model binding memakai kolom slug) */
    public function show(Request $request, Kuliner $kuliner): View
    {
        $kuliner->load(['daerah', 'kategori']);
        $kuliner->loadAvg('ulasan', 'rating')->loadCount('ulasan');

        $user = $request->user();

        return view('kuliner.show', [
            'kuliner' => $kuliner,
            'ulasan' => $kuliner->ulasan()->with('user')->latest()->latest('id')->get(),
            // data milik pengguna yang sedang login (null / false untuk tamu)
            'ulasanSaya' => $user ? $kuliner->ulasan()->where('user_id', $user->id)->first() : null,
            'sudahFavorit' => $user ? $kuliner->favorit()->where('user_id', $user->id)->exists() : false,
            // saran: kuliner lain dari daerah yang sama dulu, lalu yang sekategori (maks 4)
            'terkait' => Kuliner::with('daerah')
                ->whereKeyNot($kuliner->id)
                ->where(fn ($q) => $q->where('daerah_id', $kuliner->daerah_id)->orWhere('kategori_id', $kuliner->kategori_id))
                ->orderByRaw('daerah_id = ? DESC', [$kuliner->daerah_id])
                ->orderBy('nama')
                ->limit(4)->get(),
        ]);
    }
}
