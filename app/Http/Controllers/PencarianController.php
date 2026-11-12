<?php

namespace App\Http\Controllers;

use App\Models\Budaya;
use App\Models\Destinasi;
use App\Models\Kuliner;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PencarianController extends Controller
{
    /** Maksimal kartu per kelompok di halaman hasil (selebihnya lewat tombol "Lihat semua") */
    public const BATAS = 12;

    /**
     * GET /cari?q=
     * Mencari di KULINER + BUDAYA + DESTINASI sekaligus.
     * Search across all three content types at once (name, description, plus region name).
     */
    public function index(Request $request): View
    {
        $q = mb_substr($this->teks($request, 'q'), 0, 100);

        $kuliner = $budaya = $destinasi = collect();

        if ($q !== '') {
            // nama daerah ikut dicari: "Sabang" / "Gayo Lues" langsung menemukan isi daerah itu
            $pola = '%'.addcslashes($q, '%_\\').'%';
            $cocokDaerah = fn ($d) => $d->where('nama_daerah', 'like', $pola);

            $kuliner = Kuliner::with(['daerah', 'kategori'])->denganRating()
                ->where(fn ($w) => $w->cari($q)->orWhereHas('daerah', $cocokDaerah))
                ->orderBy('nama')->get();

            $budaya = Budaya::with(['daerah', 'kategori'])
                ->where(fn ($w) => $w->cari($q)->orWhereHas('daerah', $cocokDaerah))
                ->orderBy('nama')->get();

            $destinasi = Destinasi::with(['daerah', 'kategori'])
                ->where(fn ($w) => $w->cari($q)->orWhereHas('daerah', $cocokDaerah))
                ->orderBy('nama')->get();
        }

        return view('cari.index', [
            'q' => $q,
            'kuliner' => $kuliner,
            'budaya' => $budaya,
            'destinasi' => $destinasi,
            'batas' => self::BATAS,
            'total' => $kuliner->count() + $budaya->count() + $destinasi->count(),
        ]);
    }
}
