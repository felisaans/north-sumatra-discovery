<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tautan video "Tonton ..." (opsional), diisi admin
        Schema::table('budaya', function (Blueprint $table) {
            $table->string('video_url', 500)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('budaya', function (Blueprint $table) {
            $table->dropColumn('video_url');
        });
    }
};
