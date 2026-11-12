<?php

namespace App\Http\Controllers;

use App\Models\Budaya;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BudayaController extends Controller
{
    /** GET /budaya?q=&kategori=   (+ &partial=1 / AJAX -> hanya potongan daftar untuk live search) */
    public function index(Request $request): View
    {
        $q = $this->teks($request, 'q');
        $kategori = $this->teks($request, 'kategori');

        $budaya = Budaya::with(['daerah', 'kategori'])
            ->cari($q)
            ->filter($kategori)
            ->orderBy('nama')
            ->get();

        if ($request->ajax() || $request->boolean('partial')) {
            return view('budaya._daftar', ['budaya' => $budaya]);
        }

        return view('budaya.index', [
            'budaya' => $budaya,
            'daftarKategori' => Kategori::tipe(Kategori::TIPE_BUDAYA)->orderBy('nama_kategori')->get(),
            'q' => $q,
            'kategoriAktif' => $kategori,
        ]);
    }
}
