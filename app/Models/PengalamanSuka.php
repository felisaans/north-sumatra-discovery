<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** Satu "suka" (like) dari satu pengguna pada satu pengalaman di Beranda. */
class PengalamanSuka extends Model
{
    protected $table = 'pengalaman_suka';

    protected $fillable = ['user_id', 'pengalaman_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pengalaman(): BelongsTo
    {
        return $this->belongsTo(Pengalaman::class);
    }
}
