<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kuliner', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 150);
            $table->string('slug', 150)->unique();
            $table->text('deskripsi')->nullable();
            $table->string('bahan_utama')->nullable();
            $table->unsignedInteger('harga_min')->nullable();
            $table->unsignedInteger('harga_max')->nullable();
            $table->string('gambar', 500)->nullable();   // URL penuh (editorial = URL eksternal)
            $table->text('sejarah')->nullable();
            $table->text('cerita_budaya')->nullable();

            // Jika daerah/kategori dihapus, kuliner TIDAK ikut terhapus (kolom jadi NULL)
            $table->foreignId('daerah_id')->nullable()->constrained('daerah')->nullOnDelete();
            $table->foreignId('kategori_id')->nullable()->constrained('kategori')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kuliner');
    }
};
