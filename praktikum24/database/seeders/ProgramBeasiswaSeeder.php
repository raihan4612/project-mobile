<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProgramBeasiswaSeeder extends Seeder
{
    public function run(): void
    {
        $programs = [
            ['nama_beasiswa' => 'Beasiswa Prestasi Akademik',       'penyelenggara' => 'Kampus Merdeka',         'tahun_akademik' => '2024/2025', 'jumlah_dana' => 5000000],
            ['nama_beasiswa' => 'Beasiswa Bidikmisi',              'penyelenggara' => 'Kemendikbudristek',      'tahun_akademik' => '2024/2025', 'jumlah_dana' => 12000000],
            ['nama_beasiswa' => 'Beasiswa Hafidz Al-Quran',        'penyelenggara' => 'Yayasan Daarul Quran',   'tahun_akademik' => '2024/2025', 'jumlah_dana' => 3000000],
            ['nama_beasiswa' => 'Beasiswa Olahraga Berprestasi',   'penyelenggara' => 'KONI Pusat',            'tahun_akademik' => '2024/2025', 'jumlah_dana' => 4000000],
            ['nama_beasiswa' => 'Beasiswa Seni dan Budaya',        'penyelenggara' => 'Disdikbud',             'tahun_akademik' => '2024/2025', 'jumlah_dana' => 3500000],
            ['nama_beasiswa' => 'Beasiswa Mahasiswa Aktif',        'penyelenggara' => 'Universitas',           'tahun_akademik' => '2024/2025', 'jumlah_dana' => 2000000],
            ['nama_beasiswa' => 'Beasiswa PIP',                    'penyelenggara' => 'Kemendikbudristek',      'tahun_akademik' => '2024/2025', 'jumlah_dana' => 7200000],
            ['nama_beasiswa' => 'Beasiswa Unggulan',               'penyelenggara' => 'Kemendikbudristek',      'tahun_akademik' => '2024/2025', 'jumlah_dana' => 15000000],
            ['nama_beasiswa' => 'Beasiswa BCA',                    'penyelenggara' => 'Bank BCA',              'tahun_akademik' => '2024/2025', 'jumlah_dana' => 10000000],
            ['nama_beasiswa' => 'Beasiswa Djarum',                 'penyelenggara' => 'Djarum Foundation',      'tahun_akademik' => '2024/2025', 'jumlah_dana' => 8000000],
            ['nama_beasiswa' => 'Beasiswa Bank Indonesia',         'penyelenggara' => 'Bank Indonesia',         'tahun_akademik' => '2024/2025', 'jumlah_dana' => 6000000],
            ['nama_beasiswa' => 'Beasiswa Pertamina',              'penyelenggara' => 'Pertamina Foundation',   'tahun_akademik' => '2024/2025', 'jumlah_dana' => 9000000],
            ['nama_beasiswa' => 'Beasiswa Telkomsel',              'penyelenggara' => 'Telkomsel',             'tahun_akademik' => '2024/2025', 'jumlah_dana' => 5000000],
            ['nama_beasiswa' => 'Beasiswa Google Indonesia',       'penyelenggara' => 'Google Indonesia',      'tahun_akademik' => '2024/2025', 'jumlah_dana' => 20000000],
            ['nama_beasiswa' => 'Beasiswa Microsoft Indonesia',    'penyelenggara' => 'Microsoft Indonesia',   'tahun_akademik' => '2024/2025', 'jumlah_dana' => 18000000],
            ['nama_beasiswa' => 'Beasiswa ASEAN',                  'penyelenggara' => 'ASEAN Foundation',       'tahun_akademik' => '2024/2025', 'jumlah_dana' => 25000000],
            ['nama_beasiswa' => 'Beasiswa LPDP',                   'penyelenggara' => 'Kemendikbudristek',      'tahun_akademik' => '2024/2025', 'jumlah_dana' => 50000000],
            ['nama_beasiswa' => 'Beasiswa Yayasan Amal Insani',    'penyelenggara' => 'Yayasan Amal Insani',    'tahun_akademik' => '2024/2025', 'jumlah_dana' => 3000000],
            ['nama_beasiswa' => 'Beasiswa Data Science',           'penyelenggara' => 'DQLab',                 'tahun_akademik' => '2024/2025', 'jumlah_dana' => 5000000],
            ['nama_beasiswa' => 'Beasiswa Pemimpin Muda',          'penyelenggara' => 'Indonesia Maju Foundation', 'tahun_akademik' => '2024/2025', 'jumlah_dana' => 6500000],
            ['nama_beasiswa' => 'Beasiswa Mandiri',                'penyelenggara' => 'Bank Mandiri',          'tahun_akademik' => '2024/2025', 'jumlah_dana' => 7500000],
            ['nama_beasiswa' => 'Beasiswa BNI',                    'penyelenggara' => 'Bank BNI',              'tahun_akademik' => '2024/2025', 'jumlah_dana' => 7000000],
        ];

        DB::table('program_beasiswa')->insert($programs);

        $this->command->info('Berhasil membuat ' . count($programs) . ' program beasiswa.');
    }
}
