<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kategori extends Model
{
    public const TIPE_KULINER = 'kuliner';

    public const TIPE_BUDAYA = 'budaya';

    public const TIPE_DESTINASI = 'destinasi';

    protected $table = 'kategori';

    protected $fillable = ['nama_kategori', 'slug', 'tipe'];

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

    /* ---------- Scope ---------- */

    /** Kategori->tipe('kuliner')->get() */
    public function scopeTipe(Builder $query, string $tipe): Builder
    {
        return $query->where('tipe', $tipe);
    }
}
