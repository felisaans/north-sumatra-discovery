<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    protected $table = 'berita';

    protected $fillable = ['judul', 'ringkasan', 'label', 'tanggal'];

    protected function casts(): array
    {
        return [
            // jadi objek Carbon: $berita->tanggal->translatedFormat('d F Y')
            'tanggal' => 'date',
        ];
    }
}
