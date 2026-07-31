<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('beasiswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mhs')->onDelete('cascade');
            $table->string('nama_beasiswa', 200);
            $table->string('penyelenggara', 150);
            $table->string('tahun_akademik', 20);
            $table->decimal('jumlah_dana', 12, 2)->default(0);
            $table->enum('status', ['Diajukan', 'Disetujui', 'Ditolak'])->default('Diajukan');
            $table->date('tanggal_pengajuan');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('beasiswa');
    }
};
