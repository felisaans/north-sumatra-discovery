<?php

namespace App\Http\Controllers;

use App\Models\Daerah;
use App\Models\Destinasi;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DestinasiController extends Controller
{
    /** GET /destinasi?daerah=   (+ &partial=1 / AJAX -> hanya potongan daftar saat chip diklik) */
    public function index(Request $request): View
    {
        $daerah = $this->teks($request, 'daerah');

        $destinasi = Destinasi::with(['daerah', 'kategori'])
            ->filter($daerah)
            ->orderBy('nama')
            ->get();

        if ($request->ajax() || $request->boolean('partial')) {
            return view('destinasi._daftar', ['destinasi' => $destinasi]);
        }

        return view('destinasi.index', [
            'destinasi' => $destinasi,
            // chip daerah TANPA "Seluruh Wilayah Sumatera Utara"
            'daerahChip' => Daerah::untukChip()->get(),
            'daerahAktif' => $daerah,
        ]);
    }
}
