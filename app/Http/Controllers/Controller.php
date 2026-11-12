<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

abstract class Controller
{
    /**
     * Ambil parameter query sebagai teks biasa. Bila pengunjung mengetik ?q[]=x (array),
     * hasilnya '' -- bukan error 500 karena tipe data.
     */
    protected function teks(Request $request, string $kunci): string
    {
        $nilai = $request->query($kunci);

        return is_string($nilai) ? trim($nilai) : '';
    }
}
