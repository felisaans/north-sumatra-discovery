<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Daerah extends Model
{
    /** Slug baris khusus "Seluruh Wilayah Sumatera Utara" */
    public const SLUG_SEMUA = 'semua';

    // Nama tabel kita bahasa Indonesia (bukan jamak Inggris "daerahs"), jadi wajib ditulis eksplisit
    protected $table = 'daerah';

    protected $fillable = ['nama_daerah', 'slug'];

    /* ---------- Relasi ---------- */

    public function kuliner(): HasMany
    {
        return $this->hasMany(Kuliner::class);
    }

    public function budaya(): HasMany
    {
        return $this->hasMany(Budaya::class);
    }

    public function destinasi(): HasMany
    {
        return $this->hasMany(Destinasi::class);
    }

    public function pengalaman(): HasMany
    {
        return $this->hasMany(Pengalaman::class);
    }

    /* ---------- Scope ---------- */

    /** Daerah untuk chip filter: baris 'semua' tidak ditampilkan sebagai chip */
    public function scopeUntukChip(Builder $query): Builder
    {
        return $query->where('slug', '!=', self::SLUG_SEMUA)->orderBy('id');
    }

    public function isSeluruhAceh(): bool
    {
        return $this->slug === self::SLUG_SEMUA;
    }
}
