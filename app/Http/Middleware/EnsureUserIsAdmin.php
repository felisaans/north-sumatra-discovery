<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware "admin": hanya pengguna dengan peran 'admin' yang boleh lewat.
 * Setara dengan fungsi is_admin() + RLS "admin kelola ..." di Supabase lama.
 * Dipasang SESUDAH middleware 'auth' (tamu sudah diarahkan ke /masuk oleh 'auth').
 */
class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Bukan admin -> 403 "Akses Ditolak" (menggantikan blok aksesDitolak di admin.js lama)
        abort_unless($request->user()?->isAdmin(), 403, 'Akses Ditolak');

        return $next($request);
    }
}
