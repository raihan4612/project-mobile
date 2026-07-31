<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambah kolom program_beasiswa_id (nullable dulu)
        Schema::table('beasiswa', function (Blueprint $table) {
            $table->foreignId('program_beasiswa_id')->nullable()->after('id')
                  ->constrained('program_beasiswa')->onDelete('restrict');
        });

        // 2. Migrasi data: buat program_beasiswa dari data unik di tabel beasiswa
        $programs = DB::table('beasiswa')
            ->select('nama_beasiswa', 'penyelenggara', 'tahun_akademik', 'jumlah_dana')
            ->distinct()
            ->get();

        foreach ($programs as $prog) {
            $id = DB::table('program_beasiswa')->insertGetId([
                'nama_beasiswa'  => $prog->nama_beasiswa,
                'penyelenggara'  => $prog->penyelenggara,
                'tahun_akademik' => $prog->tahun_akademik,
                'jumlah_dana'    => $prog->jumlah_dana,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);

            // Update beasiswa yang cocok
            DB::table('beasiswa')
                ->where('nama_beasiswa', $prog->nama_beasiswa)
                ->where('penyelenggara', $prog->penyelenggara)
                ->where('tahun_akademik', $prog->tahun_akademik)
                ->where('jumlah_dana', $prog->jumlah_dana)
                ->update(['program_beasiswa_id' => $id]);
        }

        // 3. Hapus kolom lama
        Schema::table('beasiswa', function (Blueprint $table) {
            $table->dropColumn(['nama_beasiswa', 'penyelenggara', 'tahun_akademik', 'jumlah_dana']);
        });

        // 4. Set program_beasiswa_id jadi NOT NULL
        Schema::table('beasiswa', function (Blueprint $table) {
            $table->foreignId('program_beasiswa_id')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('beasiswa', function (Blueprint $table) {
            $table->string('nama_beasiswa', 200)->after('mahasiswa_id');
            $table->string('penyelenggara', 150)->after('nama_beasiswa');
            $table->string('tahun_akademik', 20)->after('penyelenggara');
            $table->decimal('jumlah_dana', 12, 2)->default(0)->after('tahun_akademik');
        });

        // Kembalikan data
        $programs = DB::table('program_beasiswa')->get();
        foreach ($programs as $prog) {
            DB::table('beasiswa')
                ->where('program_beasiswa_id', $prog->id)
                ->update([
                    'nama_beasiswa'  => $prog->nama_beasiswa,
                    'penyelenggara'  => $prog->penyelenggara,
                    'tahun_akademik' => $prog->tahun_akademik,
                    'jumlah_dana'    => $prog->jumlah_dana,
                ]);
        }

        Schema::table('beasiswa', function (Blueprint $table) {
            $table->dropConstrainedForeignId('program_beasiswa_id');
        });

        Schema::dropIfExists('program_beasiswa');
    }
};
