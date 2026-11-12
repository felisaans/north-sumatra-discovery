<?php

namespace App\Models;

use App\Models\Concerns\PunyaUrlGambar;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Destinasi extends Model
{
    use PunyaUrlGambar;

    protected $table = 'destinasi';

    protected $fillable = [
        'nama', 'slug', 'deskripsi', 'gambar', 'jenis_wisata', 'maps_url', 'daerah_id', 'kategori_id',
    ];

    /* ---------- Relasi ---------- */

    public function daerah(): BelongsTo
    {
        return $this->belongsTo(Daerah::class);
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }

    /* ---------- Scope ---------- */

    /** Cari di nama, deskripsi, dan jenis wisata (dipakai pencarian menyeluruh /cari) */
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
                ->orWhere('jenis_wisata', 'like', $pola);
        });
    }

    /** Filter berdasarkan SLUG daerah (kosong = tampilkan semua) */
    public function scopeFilter(Builder $query, ?string $daerah = null): Builder
    {
        return $query->when($daerah, fn (Builder $q) => $q->whereHas('daerah', fn (Builder $d) => $d->where('slug', $daerah)));
    }

    /* ---------- Accessor ---------- */

    protected function gambarUrl(): Attribute
    {
        return Attribute::get(fn () => $this->urlDari($this->gambar));
    }
}
