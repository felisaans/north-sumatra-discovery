<?php

namespace App\Http\Controllers;

use App\Models\Kuliner;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /** GET /robots.txt : admin & halaman pribadi tidak diindeks; sitemap ditunjuk dengan alamat penuh */
    public function robots(): Response
    {
        $isi = "User-agent: *\nDisallow: /admin\nDisallow: /masuk\nDisallow: /favorit\n\nSitemap: ".route('sitemap')."\n";

        return response($isi, 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }

    /** GET /sitemap.xml : halaman utama + setiap kuliner (diambil dari database, jadi selalu mutakhir) */
    public function sitemap(): Response
    {
        $halaman = collect([
            [route('beranda'), null],
            [route('kuliner.index'), null],
            [route('budaya.index'), null],
            [route('destinasi.index'), null],
        ])->concat(
            Kuliner::orderBy('nama')->get(['slug', 'updated_at'])
                ->map(fn ($k) => [route('kuliner.show', $k->slug), $k->updated_at?->toAtomString()])
        );

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n".'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
        foreach ($halaman as [$alamat, $diubah]) {
            $xml .= '  <url><loc>'.e($alamat).'</loc>'.($diubah ? '<lastmod>'.$diubah.'</lastmod>' : '').'</url>'."\n";
        }
        $xml .= '</urlset>'."\n";

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }
}
