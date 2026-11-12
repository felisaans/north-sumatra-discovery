<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\View\View;

/**
 * Autentikasi tulis-sendiri (tanpa starter kit): tampil form, masuk, daftar, keluar.
 * Pengganti auth.signInWithPassword / signUp / signOut dari Supabase.
 */
class AuthController extends Controller
{
    /** GET /masuk : satu halaman, dua tab (Masuk & Daftar) */
    public function form(Request $request): View
    {
        // Ingat halaman asal (mis. detail kuliner) supaya setelah login kembali ke sana,
        // seperti document.referrer di login.js lama. Selalu diperbarui bila datang dari halaman
        // lain di situs ini; dibiarkan bila tidak ada halaman asal (buka /masuk langsung) atau
        // bila halaman ini ditampilkan ulang setelah salah isi.
        $asal = $request->session()->previousUrl();
        if ($asal && $asal !== url()->current() && str_starts_with($asal, url('/')) && ! str_starts_with($asal, route('masuk'))) {
            $request->session()->put('url.intended', $asal);
        }

        return view('auth.masuk');
    }

    /** POST /masuk */
    public function login(LoginRequest $request): RedirectResponse
    {
        // Batasi tebak-tebakan sandi: maks 5 percobaan gagal per menit untuk kombinasi email + IP
        $kunci = 'masuk|'.mb_strtolower((string) $request->input('email')).'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($kunci, 5)) {
            $detik = RateLimiter::availableIn($kunci);

            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => "Terlalu banyak percobaan masuk. Coba lagi dalam {$detik} detik."], 'masuk');
        }

        if (! Auth::attempt($request->only('email', 'password'), $request->boolean('ingat'))) {
            RateLimiter::hit($kunci, 60);

            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Email atau kata sandi salah.'], 'masuk');
        }

        RateLimiter::clear($kunci);

        // ganti ID sesi setelah login (mencegah session fixation)
        $request->session()->regenerate();

        return redirect()->intended(route('beranda'));
    }

    /** POST /daftar : buat akun lalu langsung masuk (Keputusan 4: tanpa verifikasi email) */
    public function register(RegisterRequest $request): RedirectResponse
    {
        // hanya name, email, password yang lolos ($fillable); 'peran' otomatis 'user' dari database
        $user = User::create($request->validated());

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended(route('beranda'));
    }

    /** POST /keluar */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('beranda');
    }
}
