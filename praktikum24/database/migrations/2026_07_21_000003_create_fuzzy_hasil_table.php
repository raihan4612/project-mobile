<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fuzzy_hasil', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mhs')->onDelete('cascade')->unique();
            $table->decimal('nilai_fuzzy', 5, 2)->nullable();
            $table->string('hasil_rekomendasi', 50)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fuzzy_hasil');
    }
};
