<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // tautan halaman (admin) memakai gaya Bootstrap 5
        Paginator::useBootstrapFive();

        // Paksa https di produksi supaya url() & asset() tidak menghasilkan http://
        // Force https in production so generated URLs use https
        // GANTI DI SINI: sesuaikan nama environment dengan APP_ENV di .env kamu
        if (app()->environment(['production', 'produksi'])) {
            URL::forceScheme('https');
        }
    }
}