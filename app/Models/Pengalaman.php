<?php

namespace App\Models;

use App\Models\Concerns\PunyaUrlGambar;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pengalaman extends Model
{
    use PunyaUrlGambar;

    protected $table = 'pengalaman';

    protected $fillable = ['nama', 'teks', 'foto', 'email', 'daerah_id'];

    public function daerah(): BelongsTo
    {
        return $this->belongsTo(Daerah::class);
    }

    public function suka(): HasMany
    {
        return $this->hasMany(PengalamanSuka::class);
    }

    /** $pengalaman->foto_url (path di disk 'public' diubah jadi URL /storage/...) */
    protected function fotoUrl(): Attribute
    {
        return Attribute::get(fn () => $this->urlDari($this->foto));
    }
}
