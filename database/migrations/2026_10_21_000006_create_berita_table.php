<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('berita', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 200);
            $table->text('ringkasan')->nullable();
            $table->string('label', 100)->nullable();    // badge kecil, mis. "Kuliner Legendaris"
            $table->date('tanggal');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('berita');
    }
};
