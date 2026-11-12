<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Satu tabel kategori dipakai tiga jenis konten, dibedakan kolom `tipe`.
        Schema::create('kategori', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori', 100);
            $table->string('slug', 100);
            $table->enum('tipe', ['kuliner', 'budaya', 'destinasi']);
            $table->timestamps();

            // slug boleh sama asal tipenya beda
            $table->unique(['tipe', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kategori');
    }
};
