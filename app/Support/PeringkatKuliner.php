<?php

namespace App\Support;

use App\Models\Kuliner;

/**
 * Top 10 Kuliner berdasarkan penilaian pengguna: BINTANG (ulasan) + FAVORIT.
 *
 * skor = bintang berbobot + favorit
 *   bintang berbobot = (n * rata-rata + M * rata-rata semua kuliner) / (n + M)    (M = 3)
 *       -> kuliner dengan 1 ulasan 5 bintang tidak otomatis mengalahkan yang 40 ulasan 4,9
 *   favorit          = f / (f + 5)  (0 sampai < 1; makin banyak favorit makin tinggi, tapi menjenuh)
 * Hanya kuliner yang sudah punya minimal 1 ulasan atau 1 favorit yang bisa masuk daftar.
 * Seri skor -> yang favoritnya lebih banyak, lalu id lebih kecil.
 */
class PeringkatKuliner
{
    private const BOBOT_ULASAN = 3;
    private const BATAS = 10;

    /** @var array<int,int>|null  [id kuliner => peringkat], dihitung sekali per permintaan */
    private static ?array $cache = null;

    public static function untuk(int|string|null $id): ?int
    {
        self::$cache ??= self::hitung(self::ambilData());

        return self::$cache[(int) $id] ?? null;
    }

    /** @return array<int,array{id:int,rata:?float,ulasan:int,favorit:int}> */
    private static function ambilData(): array
    {
        return Kuliner::query()
            ->select('kuliner.id')
            ->withAvg('ulasan', 'rating')
            ->withCount(['ulasan', 'favorit'])
            ->get()
            ->map(fn ($k) => [
                'id' => (int) $k->id,
                'rata' => $k->ulasan_avg_rating !== null ? (float) $k->ulasan_avg_rating : null,
                'ulasan' => (int) $k->ulasan_count,
                'favorit' => (int) $k->favorit_count,
            ])->all();
    }

    /**
     * Murni (tanpa database) supaya mudah diuji.
     *
     * @param  array<int,array{id:int,rata:?float,ulasan:int,favorit:int}>  $baris
     * @return array<int,int>  [id => peringkat]
     */
    public static function hitung(array $baris): array
    {
        $dinilai = array_values(array_filter($baris, fn ($b) => $b['ulasan'] > 0 || $b['favorit'] > 0));
        if ($dinilai === []) {
            return [];
        }

        $adaUlasan = array_filter($dinilai, fn ($b) => $b['ulasan'] > 0);
        $rataSemua = $adaUlasan
            ? array_sum(array_map(fn ($b) => $b['rata'], $adaUlasan)) / count($adaUlasan)
            : 0.0;

        foreach ($dinilai as &$b) {
            $n = $b['ulasan'];
            $bintang = $n > 0
                ? ($n * $b['rata'] + self::BOBOT_ULASAN * $rataSemua) / ($n + self::BOBOT_ULASAN)
                : $rataSemua;
            $b['skor'] = $bintang + $b['favorit'] / ($b['favorit'] + 5);
        }
        unset($b);

        usort($dinilai, fn ($a, $c) => [$c['skor'], $c['favorit'], $a['id']] <=> [$a['skor'], $a['favorit'], $c['id']]);

        $peringkat = [];
        foreach (array_slice($dinilai, 0, self::BATAS) as $i => $b) {
            $peringkat[$b['id']] = $i + 1;
        }

        return $peringkat;
    }
}
