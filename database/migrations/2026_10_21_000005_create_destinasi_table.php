<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('destinasi', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 150);
            $table->string('slug', 150)->unique();
            $table->text('deskripsi')->nullable();
            $table->string('gambar', 500)->nullable();
            $table->string('jenis_wisata')->nullable();  // label ikon, mis. "Wisata Pantai"

            $table->foreignId('daerah_id')->nullable()->constrained('daerah')->nullOnDelete();
            $table->foreignId('kategori_id')->nullable()->constrained('kategori')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('destinasi');
    }
};
