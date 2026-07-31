<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMhsTable extends Migration
{
    public function up()
    {
        Schema::create('mhs', function (Blueprint $table) {
            $table->id();                                           // PK - auto increment
            $table->string('nim', 20)->unique();                    // Nomor Induk Mahasiswa
            $table->string('nama', 100);                            // Nama lengkap
            $table->enum('jenis_kelamin', ['L', 'P']);              // Jenis kelamin
            $table->date('tanggal_lahir');                          // Tanggal lahir
            $table->string('tempat_lahir', 100);                    // Tempat lahir
            $table->text('alamat');                                 // Alamat lengkap
            $table->string('kota', 100);                            // Kota
            $table->string('provinsi', 100);                        // Provinsi
            $table->string('kode_pos', 10)->nullable();             // Kode pos
            $table->string('no_hp', 20);                            // Nomor HP
            $table->string('email', 100)->unique();                 // Email
            $table->string('prodi', 100);                           // Program studi
            $table->string('fakultas', 100);                        // Fakultas
            $table->integer('semester')->default(1);                // Semester saat ini
            $table->string('tahun_masuk', 4);                       // Tahun masuk / angkatan
            $table->string('status', 20)->default('Aktif');         // Status mahasiswa
            $table->string('foto')->nullable();                     // Path foto profil
            $table->timestamps();                                   // created_at & updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('mhs');
    }
}