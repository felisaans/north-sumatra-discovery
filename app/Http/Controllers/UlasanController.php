<?php

namespace App\Http\Controllers;

use App\Http\Requests\UlasanRequest;
use App\Models\Kuliner;
use App\Models\Ulasan;
use Illuminate\Http\RedirectResponse;

class UlasanController extends Controller
{
    /** POST /kuliner/{kuliner:slug}/ulasan  (wajib login) */
    public function store(UlasanRequest $request, Kuliner $kuliner): RedirectResponse
    {
        // 1 pengguna = 1 ulasan per kuliner: bila sudah ada -> diperbarui (pengganti upsert Supabase).
        // user_id SELALU dari akun yang login, bukan dari input form (anti-pemalsuan).
        Ulasan::updateOrCreate(
            ['user_id' => $request->user()->id, 'kuliner_id' => $kuliner->id],
            ['rating' => $request->validated('rating'), 'komentar' => $request->validated('komentar')]
        );

        return back()->with('sukses', 'Ulasanmu sudah disimpan.');
    }
}
