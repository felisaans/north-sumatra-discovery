<?php

namespace App\Models;

use App\Models\Concerns\PunyaUrlGambar;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Support\PeringkatKuliner;

class Kuliner extends Model
{
    use PunyaUrlGambar;

    protected $table = 'kuliner';

    protected $fillable = [
        'nama', 'slug', 'deskripsi', 'bahan_utama', 'harga_min', 'harga_max',
        'gambar', 'sejarah', 'cerita_budaya', 'daerah_id', 'kategori_id',
        'spot_1_nama', 'spot_1_url', 'spot_2_nama', 'spot_2_url', 'spot_3_nama', 'spot_3_url',
    ];

    /** URL kuliner memakai slug (/kuliner/soto-medan), bukan id */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * "Rekomendasi Spot Kuliner": maksimal 3 tempat makan yang tautannya diisi admin.
     * Hanya tautan http/https yang dipakai; bila nama kosong, nama situs (host) dijadikan label.
     * @return array<int, array{nama: string, url: string}>
     */
    public function spotRekomendasi(): array
    {
        $daftar = [];
        foreach ([1, 2, 3] as $n) {
            $url = trim((string) $this->{"spot_{$n}_url"});
            if (! preg_match('#^https?://#i', $url)) {
                continue;
            }
            $nama = trim((string) $this->{"spot_{$n}_nama"});
            if ($nama === '') {
                $nama = preg_replace('/^www\./i', '', (string) parse_url($url, PHP_URL_HOST)) ?: 'Spot '.$n;
            }
            $daftar[] = ['nama' => $nama, 'url' => $url];
        }

        return $daftar;
    }

    /* ---------- Relasi ---------- */

    public function daerah(): BelongsTo
    {
        return $this->belongsTo(Daerah::class);
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }

    public function ulasan(): HasMany
    {
        return $this->hasMany(Ulasan::class);
    }

    public function favorit(): HasMany
    {
        return $this->hasMany(Favorit::class);
    }

    /* ---------- Scope (pengganti query Supabase di kuliner.js) ---------- */

    /** Rata-rata & jumlah ulasan: pengganti view `kuliner_rating` di Supabase */
    public function scopeDenganRating(Builder $query): Builder
    {
        return $query->withAvg('ulasan', 'rating')->withCount('ulasan');
    }

    /** Cari di nama, deskripsi, dan bahan utama (setara .or('nama.ilike...') lama) */
    public function scopeCari(Builder $query, ?string $kata): Builder
    {
        $kata = trim((string) $kata);
        if ($kata === '') {
            return $query;
        }

        // escape karakter khusus LIKE (% _ \) supaya dianggap huruf biasa
        $pola = '%'.addcslashes($kata, '%_\\').'%';

        return $query->where(function (Builder $q) use ($pola) {
            $q->where('nama', 'like', $pola)
                ->orWhere('deskripsi', 'like', $pola)
                ->orWhere('bahan_utama', 'like', $pola);
        });
    }

    /** Filter berdasarkan SLUG daerah dan/atau SLUG kategori (kosong = abaikan) */
    public function scopeFilter(Builder $query, ?string $daerah = null, ?string $kategori = null): Builder
    {
        return $query
            ->when($daerah, fn (Builder $q) => $q->whereHas('daerah', fn (Builder $d) => $d->where('slug', $daerah)))
            ->when($kategori, fn (Builder $q) => $q->whereHas('kategori', fn (Builder $k) => $k->where('slug', $kategori)));
    }

    /**
     * Urutan daftar: nama (A-Z, bawaan) | rating (tertinggi dulu; belum dinilai di akhir) | terbaru.
     * Nilai lain diabaikan -> kembali ke urutan nama. Butuh denganRating() untuk mode rating.
     */
    public function scopeUrutkan(Builder $query, ?string $mode): Builder
    {
        return match ($mode) {
            'rating' => $query->orderByRaw('ulasan_avg_rating IS NULL')->orderByDesc('ulasan_avg_rating')->orderBy('nama'),
            'terbaru' => $query->latest()->latest('id'),
            default => $query->orderBy('nama'),
        };
    }

    /* ---------- Accessor ---------- */

    /** $kuliner->gambar_url */
    protected function gambarUrl(): Attribute
    {
        return Attribute::get(fn () => $this->urlDari($this->gambar));
    }

    /** Peringkat 1-10 (null bila di luar Top 10). Aturan hitung: App\Support\PeringkatKuliner */
    protected function peringkat(): Attribute
    {
        return Attribute::get(fn () => PeringkatKuliner::untuk($this->getKey()));
    }

    /** Rata-rata rating 1 desimal (null bila belum ada ulasan). Butuh scope denganRating() */
    protected function ratingRata(): Attribute
    {
        return Attribute::get(fn () => isset($this->ulasan_avg_rating)
            ? round((float) $this->ulasan_avg_rating, 1)
            : null);
    }

    /** Jumlah ulasan (0 bila belum ada). Butuh scope denganRating() */
    protected function jumlahUlasan(): Attribute
    {
        return Attribute::get(fn () => (int) ($this->ulasan_count ?? 0));
    }
}
