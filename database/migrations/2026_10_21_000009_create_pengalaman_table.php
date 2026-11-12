<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Cerita + foto dari pengunjung (boleh tamu, tanpa akun)
        Schema::create('pengalaman', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->text('teks');                         // format gabungan: "📍 destinasi — ★★★☆☆ + cerita" (Keputusan 9)
            $table->string('foto');                       // path di disk 'public', mis. pengalaman/abc.jpg
            $table->string('email', 150)->nullable();     // terisi bila pengunggah sedang login
            $table->foreignId('daerah_id')->nullable()->constrained('daerah')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengalaman');
    }
};
