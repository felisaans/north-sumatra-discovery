<?php

namespace App\Http\Controllers;

use App\Models\Favorit;
use App\Models\Kuliner;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FavoritController extends Controller
{
    /** GET /favorit (wajib login) -> kuliner yang disimpan pengguna, yang terakhir disimpan di atas */
    public function index(Request $request): View
    {
        $id = $request->user()->id;

        // select() harus SEBELUM denganRating(): kalau sesudahnya, kolom rata-rata & jumlah ulasan ikut terhapus
        $kuliner = Kuliner::select('kuliner.*')
            ->with(['daerah', 'kategori'])
            ->denganRating()
            ->join('favorit', 'favorit.kuliner_id', '=', 'kuliner.id')
            ->where('favorit.user_id', $id)
            ->orderByDesc('favorit.created_at')->orderByDesc('favorit.id')
            ->get();

        return view('favorit.index', ['kuliner' => $kuliner]);
    }

    /** POST /kuliner/{kuliner:slug}/favorit  (wajib login) -> simpan bila belum, hapus bila sudah */
    public function toggle(Request $request, Kuliner $kuliner): JsonResponse|RedirectResponse
    {
        $kunci = ['user_id' => $request->user()->id, 'kuliner_id' => $kuliner->id];

        $favorit = Favorit::where($kunci)->first();

        if ($favorit) {
            $favorit->delete();
            $sudahFavorit = false;
        } else {
            Favorit::create($kunci);
            $sudahFavorit = true;
        }

        if ($request->expectsJson()) {
            return response()->json(['favorit' => $sudahFavorit]);
        }

        return back();
    }
}
