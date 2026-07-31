<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeminjamanTable extends Migration
{
    public function up()
    {
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();
            $table->string('kode_peminjaman', 20)->unique();

            // Foreign key ke tabel mhs (mahasiswa)
            $table->foreignId('mahasiswa_id')
                  ->constrained('mhs')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');  // tidak bisa hapus mahasiswa jika masih ada peminjaman

            // Foreign key ke tabel buku
            $table->foreignId('buku_id')
                  ->constrained('buku')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');

            $table->date('tanggal_pinjam');
            $table->date('tanggal_kembali_rencana');
            $table->date('tanggal_kembali_aktual')->nullable();  // diisi saat buku dikembalikan
            $table->enum('status', ['Dipinjam', 'Dikembalikan', 'Terlambat'])->default('Dipinjam');
            $table->integer('denda')->default(0);  // Rp 1.000/hari jika terlambat
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('peminjaman');
    }
}