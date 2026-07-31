<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom 'level' ke tabel hak_akses.
     * Level menentukan hierarki akses:
     *   1 = Admin, 2 = Petugas, 3 = Mahasiswa, 4 = Guest
     */
    public function up(): void
    {
        Schema::table('hak_akses', function (Blueprint $table) {
            // Kolom level: integer 1–4, unik per role
            $table->unsignedTinyInteger('level')
                  ->default(4)
                  ->comment('1=Admin, 2=Petugas, 3=Mahasiswa, 4=Guest')
                  ->after('nama_role');

            // Index agar query by level lebih cepat
            $table->index('level');
        });
    }

    public function down(): void
    {
        Schema::table('hak_akses', function (Blueprint $table) {
            $table->dropIndex(['level']);
            $table->dropColumn('level');
        });
    }
};