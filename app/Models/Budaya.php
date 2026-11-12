<?php

namespace App\Models;

use App\Models\Concerns\PunyaUrlGambar;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Budaya extends Model
{
    use PunyaUrlGambar;

    protected $table = 'budaya';

    protected $fillable = [
        'nama', 'slug', 'deskripsi', 'gambar', 'sorotan', 'video_url', 'daerah_id', 'kategori_id',
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

    /** Cari di nama dan deskripsi */
    public function scopeCari(Builder $query, ?string $kata): Builder
    {
        $kata = trim((string) $kata);
        if ($kata === '') {
            return $query;
        }

        $pola = '%'.addcslashes($kata, '%_\\').'%';

        return $query->where(function (Builder $q) use ($pola) {
            $q->where('nama', 'like', $pola)->orWhere('deskripsi', 'like', $pola);
        });
    }

    /** Filter berdasarkan SLUG kategori (kosong = abaikan) */
    public function scopeFilter(Builder $query, ?string $kategori = null): Builder
    {
        return $query->when($kategori, fn (Builder $q) => $q->whereHas('kategori', fn (Builder $k) => $k->where('slug', $kategori)));
    }

    /* ---------- Accessor ---------- */

    protected function gambarUrl(): Attribute
    {
        return Attribute::get(fn () => $this->urlDari($this->gambar));
    }
}
