<?php

use App\Http\Middleware\EnsureUserIsAdmin;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Alias middleware 'admin' -> dipakai di routes/web.php (grup /admin)
        $middleware->alias([
            'admin' => EnsureUserIsAdmin::class,
        ]);

        // Tamu yang membuka halaman wajib-login diarahkan ke halaman /masuk
        // (project ini tidak punya route bernama "login", jadi harus diatur di sini)
        $middleware->redirectGuestsTo(fn () => route('masuk'));

        // Pengguna yang sudah login tapi membuka /masuk diarahkan ke Beranda
        $middleware->redirectUsersTo(fn () => route('beranda'));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
