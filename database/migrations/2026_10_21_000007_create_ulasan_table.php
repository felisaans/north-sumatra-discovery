<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ulasan', function (Blueprint $table) {
            $table->id();
            // Ulasan ikut terhapus bila akun / kuliner dihapus
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('kuliner_id')->constrained('kuliner')->cascadeOnDelete();
            $table->unsignedTinyInteger('rating');
            $table->text('komentar')->nullable();
            $table->timestamps();

            // 1 pengguna hanya boleh 1 ulasan per kuliner (kirim ulang = perbarui)
            $table->unique(['user_id', 'kuliner_id']);
        });

        // Rating wajib 1-5. CHECK hanya ditambahkan di MariaDB/MySQL
        // (SQLite untuk testing tidak mendukung ALTER TABLE ... ADD CONSTRAINT).
        if (in_array(DB::getDriverName(), ['mariadb', 'mysql'], true)) {
            DB::statement('ALTER TABLE ulasan ADD CONSTRAINT ulasan_rating_check CHECK (rating BETWEEN 1 AND 5)');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ulasan');
    }
};
