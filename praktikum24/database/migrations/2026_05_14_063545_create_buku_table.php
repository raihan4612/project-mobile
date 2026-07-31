<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBukuTable extends Migration
{
    public function up()
    {
        Schema::create('buku', function (Blueprint $table) {
            $table->id();
            $table->string('kode_buku', 20)->unique();
            $table->string('judul', 200);
            $table->string('pengarang', 100);
            $table->string('penerbit', 100);
            $table->string('tahun_terbit', 4);
            $table->string('kategori', 50);
            $table->integer('jumlah_stok')->default(1);
            $table->integer('jumlah_tersedia')->default(1);  // stok dikurangi saat dipinjam
            $table->text('deskripsi')->nullable();
            $table->enum('status', ['Tersedia', 'Habis'])->default('Tersedia');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('buku');
    }
}