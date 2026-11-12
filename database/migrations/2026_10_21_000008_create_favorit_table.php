<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('favorit', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('kuliner_id')->constrained('kuliner')->cascadeOnDelete();
            $table->timestamps();

            // mencegah favorit ganda
            $table->unique(['user_id', 'kuliner_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('favorit');
    }
};
