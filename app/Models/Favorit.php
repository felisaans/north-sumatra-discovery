<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Favorit extends Model
{
    protected $table = 'favorit';

    protected $fillable = ['user_id', 'kuliner_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function kuliner(): BelongsTo
    {
        return $this->belongsTo(Kuliner::class);
    }
}
