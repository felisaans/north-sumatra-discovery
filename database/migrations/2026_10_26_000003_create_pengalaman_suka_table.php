<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Suka (like) pada pengalaman di Beranda: satu pengguna maksimal satu suka per pengalaman
        Schema::create('pengalaman_suka', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('pengalaman_id')->constrained('pengalaman')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['user_id', 'pengalaman_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengalaman_suka');
    }
};
