<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

// 'peran' SENGAJA tidak ada di Fillable: pengguna tidak boleh menaikkan perannya sendiri
// lewat form/request (setara trigger protect_profile_role di Supabase lama).
#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const PERAN_USER = 'user';

    public const PERAN_ADMIN = 'admin';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /* ---------- Relasi ---------- */

    public function ulasan(): HasMany
    {
        return $this->hasMany(Ulasan::class);
    }

    public function favorit(): HasMany
    {
        return $this->hasMany(Favorit::class);
    }

    /** Daftar kuliner yang difavoritkan (lewat tabel pivot `favorit`) */
    public function kulinerFavorit(): BelongsToMany
    {
        return $this->belongsToMany(Kuliner::class, 'favorit')->withTimestamps();
    }

    /* ---------- Helper ---------- */

    public function isAdmin(): bool
    {
        return $this->peran === self::PERAN_ADMIN;
    }

    /** Foto Gravatar dari email (d=404: bila belum punya foto, browser jatuh ke inisial) */
    protected function avatarUrl(): Attribute
    {
        return Attribute::get(fn () => 'https://www.gravatar.com/avatar/'
            .md5(strtolower(trim($this->email))).'?d=404&s=160');
    }

    /** Inisial nama untuk avatar cadangan, mis. "Felisa Aziva" -> "FA" */
    protected function inisial(): Attribute
    {
        return Attribute::get(function () {
            $kata = preg_split('/\s+/', trim($this->name)) ?: [];
            $huruf = collect($kata)->filter()->take(2)->map(fn ($k) => Str::upper(Str::substr($k, 0, 1)));

            return $huruf->implode('') ?: '?';
        });
    }
}
