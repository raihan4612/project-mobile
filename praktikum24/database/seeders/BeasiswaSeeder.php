<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BeasiswaSeeder extends Seeder
{
    public function run(): void
    {
        $mahasiswaIds = DB::table('mhs')->pluck('id');
        $programIds = DB::table('program_beasiswa')->pluck('id');
        $statusPool = ['Diajukan', 'Disetujui', 'Ditolak'];
        $now = now();

        $beasiswaData = [];
        $usedCombinations = [];

        foreach ($mahasiswaIds as $mhsId) {
            $jumlahPengajuan = rand(1, 2);

            for ($i = 0; $i < $jumlahPengajuan; $i++) {
                $progId = $programIds->random();
                $key = $mhsId . '-' . $progId;

                if (isset($usedCombinations[$key])) {
                    continue;
                }
                $usedCombinations[$key] = true;

                $status = $statusPool[array_rand($statusPool)];
                $keterangan = null;

                if ($status === 'Ditolak') {
                    $alasan = [
                        'IPK tidak memenuhi syarat minimum',
                        'Berkas tidak lengkap',
                        'Tidak lolos seleksi administrasi',
                        'Kuota sudah terpenuhi',
                        'Tidak memenuhi kriteria program',
                    ];
                    $keterangan = $alasan[array_rand($alasan)];
                } elseif ($status === 'Disetujui' && rand(0, 1)) {
                    $keterangan = 'Selamat, pengajuan diterima. Silakan lanjut ke tahap berikutnya.';
                }

                $tanggalPengajuan = now()->subDays(rand(1, 90))->format('Y-m-d');

                $beasiswaData[] = [
                    'program_beasiswa_id' => $progId,
                    'mahasiswa_id'        => $mhsId,
                    'status'              => $status,
                    'tanggal_pengajuan'   => $tanggalPengajuan,
                    'keterangan'          => $keterangan,
                    'created_at'          => $now,
                    'updated_at'          => $now,
                ];
            }
        }

        DB::table('beasiswa')->insert($beasiswaData);

        $this->command->info('Berhasil membuat ' . count($beasiswaData) . ' pengajuan beasiswa.');
    }
}
