<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tautan "Lihat di Maps" per destinasi (diisi admin lewat dashboard)
        Schema::table('destinasi', function (Blueprint $table) {
            $table->string('maps_url', 500)->nullable()->after('jenis_wisata');
        });
    }

    public function down(): void
    {
        Schema::table('destinasi', function (Blueprint $table) {
            $table->dropColumn('maps_url');
        });
    }
};
