<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PrestasiMahasiswaSeeder extends Seeder
{
    public function run(): void
    {
        $mahasiswa = DB::table('mhs')->select('id', 'nama')->orderBy('id')->get();
        $now = now();

        $lombaPool = [
            ['nama_lomba' => 'Lomba Pemrograman Web',            'penyelenggara' => 'BEM Fakultas Ilmu Komputer', 'jenis_id' => 1, 'tingkat_id' => 1],
            ['nama_lomba' => 'Hackathon Nasional',               'penyelenggara' => 'Kemenkominfo',               'jenis_id' => 1, 'tingkat_id' => 4],
            ['nama_lomba' => 'Olimpiade Matematika',             'penyelenggara' => 'Puspresnas',                 'jenis_id' => 1, 'tingkat_id' => 4],
            ['nama_lomba' => 'Kompetisi Robotik',                'penyelenggara' => 'Kemenristek',                'jenis_id' => 1, 'tingkat_id' => 4],
            ['nama_lomba' => 'Lomba Debat Bahasa Inggris',       'penyelenggara' => 'Universitas',                'jenis_id' => 2, 'tingkat_id' => 3],
            ['nama_lomba' => 'Lomba Karya Tulis Ilmiah',         'penyelenggara' => 'LPDP',                       'jenis_id' => 1, 'tingkat_id' => 4],
            ['nama_lomba' => 'Musabaqah Tilawatil Quran',        'penyelenggara' => 'Kemenag',                    'jenis_id' => 4, 'tingkat_id' => 3],
            ['nama_lomba' => 'Futsal Antar Kampus',              'penyelenggara' => 'KONI Kota',                  'jenis_id' => 3, 'tingkat_id' => 2],
            ['nama_lomba' => 'Basket Competition',                'penyelenggara' => 'KONI Provinsi',              'jenis_id' => 3, 'tingkat_id' => 3],
            ['nama_lomba' => 'Lomba Desain UI/UX',               'penyelenggara' => 'Google Indonesia',           'jenis_id' => 4, 'tingkat_id' => 5],
            ['nama_lomba' => 'Lomba Fotografi',                  'penyelenggara' => 'Disdikbud',                  'jenis_id' => 4, 'tingkat_id' => 2],
            ['nama_lomba' => 'Lomba Pidato Bahasa Inggris',       'penyelenggara' => 'EF English First',          'jenis_id' => 2, 'tingkat_id' => 4],
            ['nama_lomba' => 'Competitive Programming',           'penyelenggara' => 'Codeforces',                'jenis_id' => 1, 'tingkat_id' => 5],
            ['nama_lomba' => 'Lomba Catur',                      'penyelenggara' => 'KONI Kota',                  'jenis_id' => 3, 'tingkat_id' => 2],
            ['nama_lomba' => 'Lomba Video Pendek Kreatif',       'penyelenggara' => 'Telkomsel',                 'jenis_id' => 4, 'tingkat_id' => 4],
            ['nama_lomba' => 'Lomba Business Plan',               'penyelenggara' => 'Bank Mandiri',              'jenis_id' => 2, 'tingkat_id' => 4],
            ['nama_lomba' => 'Lomba Cyber Security',              'penyelenggara' => 'BSSN',                      'jenis_id' => 1, 'tingkat_id' => 4],
            ['nama_lomba' => 'Marathon Internasional',            'penyelenggara' => 'Jakarta Marathon',          'jenis_id' => 3, 'tingkat_id' => 5],
        ];

        $juaraPool = ['Juara 1', 'Juara 2', 'Juara 3', 'Harapan 1', null, null];

        $prestasiData = [];
        $verifikasiData = [];

        foreach ($mahasiswa as $mhs) {
            $jumlahPrestasi = rand(1, 3);
            $selectedKeys = array_rand($lombaPool, min($jumlahPrestasi, count($lombaPool)));
            if (!is_array($selectedKeys)) {
                $selectedKeys = [$selectedKeys];
            }

            foreach ($selectedKeys as $key) {
                $lomba = $lombaPool[$key];
                $tanggal = now()->subDays(rand(30, 365));

                $prestasiData[] = [
                    'mahasiswa_id'     => $mhs->id,
                    'jenis_id'         => $lomba['jenis_id'],
                    'tingkat_id'       => $lomba['tingkat_id'],
                    'nama_lomba'       => $lomba['nama_lomba'],
                    'penyelenggara'    => $lomba['penyelenggara'],
                    'tanggal'          => $tanggal,
                    'juara'            => $juaraPool[array_rand($juaraPool)],
                    'sertifikat'       => null,
                    'status_verifikasi' => rand(0, 3) > 0 ? 'Disetujui' : 'Pending',
                    'created_at'       => $now,
                    'updated_at'       => $now,
                ];
            }
        }

        foreach ($prestasiData as &$item) {
            $id = DB::table('prestasi')->insertGetId($item);
            if ($item['status_verifikasi'] === 'Disetujui') {
                $verifikasiData[] = [
                    'prestasi_id'       => $id,
                    'admin_id'          => 1,
                    'tanggal_verifikasi' => now()->subDays(rand(1, 10)),
                    'catatan'           => null,
                    'created_at'        => $now,
                    'updated_at'        => $now,
                ];
            }
        }

        if (!empty($verifikasiData)) {
            DB::table('verifikasi_prestasi')->insert($verifikasiData);
        }

        $this->command->info('Berhasil membuat ' . count($prestasiData) . ' prestasi untuk ' . $mahasiswa->count() . ' mahasiswa.');
    }
}
