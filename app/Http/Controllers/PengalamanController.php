<?php

namespace App\Http\Controllers;

use App\Http\Requests\PengalamanRequest;
use App\Models\Pengalaman;
use App\Models\PengalamanSuka;
use App\Support\FotoUpload;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;

class PengalamanController extends Controller
{
    /** POST /pengalaman  (WAJIB LOGIN; dibatasi throttle di routes/web.php) */
    public function store(PengalamanRequest $request): JsonResponse|RedirectResponse
    {
        $data = $request->validated();

        // Foto diperkecil (maks 1600 px, WebP) lalu disimpan di storage/app/public/pengalaman (dilayani lewat public/storage)
        $path = FotoUpload::simpan($request->file('foto'), 'pengalaman', 'public');

        // Format teks sama seperti versi lama: "📍 destinasi — ★★★☆☆ + baris kosong + cerita"
        $rating = (int) $data['rating'];
        $teks = '📍 '.$data['destinasi'].' — '
            .str_repeat('★', $rating).str_repeat('☆', 5 - $rating)
            ."\n\n".$data['cerita'];

        $pengalaman = Pengalaman::create([
            'nama' => $request->user()->name,   // nama pengunggah = nama akun
            'teks' => $teks,
            'foto' => $path,
            'daerah_id' => $data['daerah_id'],
            'email' => $request->user()->email,
        ])->load('daerah');

        // JS (fetch) meminta JSON untuk menambah slide baru; form biasa dikembalikan ke halaman
        if ($request->expectsJson()) {
            return response()->json([
                'id' => $pengalaman->id,
                'nama' => $pengalaman->nama,
                'teks' => $pengalaman->teks,
                'foto_url' => $pengalaman->foto_url,
                'daerah' => $pengalaman->daerah?->nama_daerah,
                'email' => $pengalaman->email,
                // Slide siap pakai untuk carousel (di-escape Blade; satu template dengan Beranda)
                'html' => Blade::render('<x-slide-pengalaman :p="$p" :aktif="true" />', ['p' => $pengalaman]),
            ], 201);
        }

        return back()->with('sukses', 'Terima kasih! Pengalamanmu sudah dibagikan.');
    }

    /** POST /pengalaman/{pengalaman}/suka  (wajib login) -> suka bila belum, batal suka bila sudah */
    public function suka(Request $request, Pengalaman $pengalaman): JsonResponse|RedirectResponse
    {
        $kunci = ['user_id' => $request->user()->id, 'pengalaman_id' => $pengalaman->id];

        $suka = PengalamanSuka::where($kunci)->first();

        if ($suka) {
            $suka->delete();
            $disuka = false;
        } else {
            PengalamanSuka::create($kunci);
            $disuka = true;
        }

        if ($request->expectsJson()) {
            return response()->json([
                'suka' => $disuka,
                'jumlah' => $pengalaman->suka()->count(),
            ]);
        }

        return back();
    }
}
