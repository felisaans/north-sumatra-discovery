<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // "Rekomendasi Spot Kuliner": 1-3 tempat makan per kuliner (nama + tautan), diisi admin
        Schema::table('kuliner', function (Blueprint $table) {
            foreach ([1, 2, 3] as $n) {
                $table->string("spot_{$n}_nama", 100)->nullable();
                $table->string("spot_{$n}_url", 500)->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('kuliner', function (Blueprint $table) {
            $table->dropColumn(['spot_1_nama', 'spot_1_url', 'spot_2_nama', 'spot_2_url', 'spot_3_nama', 'spot_3_url']);
        });
    }
};
