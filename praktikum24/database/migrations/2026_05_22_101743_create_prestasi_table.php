<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel Jenis Prestasi
        Schema::create('jenis_prestasi', function (Blueprint $table) {
            $table->id();
            $table->string('nama_jenis', 50)->unique();
            $table->timestamps();
        });

        // Tabel Tingkat Prestasi
        Schema::create('tingkat_prestasi', function (Blueprint $table) {
            $table->id();
            $table->string('nama_tingkat', 50)->unique();
            $table->timestamps();
        });

        // Tabel Prestasi Mahasiswa
        Schema::create('prestasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mhs')->onDelete('cascade');
            $table->foreignId('jenis_id')->constrained('jenis_prestasi')->onDelete('restrict');
            $table->foreignId('tingkat_id')->constrained('tingkat_prestasi')->onDelete('restrict');
            $table->string('nama_lomba', 200);
            $table->string('penyelenggara', 150);
            $table->date('tanggal');
            $table->string('juara', 50)->nullable();
            $table->string('sertifikat')->nullable();
            $table->enum('status_verifikasi', ['Pending', 'Disetujui', 'Ditolak'])->default('Pending');
            $table->timestamps();
        });

        // Tabel Verifikasi Prestasi
        Schema::create('verifikasi_prestasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prestasi_id')->constrained('prestasi')->onDelete('cascade');
            $table->foreignId('admin_id')->constrained('users')->onDelete('restrict');
            $table->date('tanggal_verifikasi');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('verifikasi_prestasi');
        Schema::dropIfExists('prestasi');
        Schema::dropIfExists('tingkat_prestasi');
        Schema::dropIfExists('jenis_prestasi');
    }
};
