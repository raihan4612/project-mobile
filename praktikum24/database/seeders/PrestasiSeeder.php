<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PrestasiSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('jenis_prestasi')->insertOrIgnore([
            ['nama_jenis' => 'Akademik',     'created_at' => now(), 'updated_at' => now()],
            ['nama_jenis' => 'Non Akademik', 'created_at' => now(), 'updated_at' => now()],
            ['nama_jenis' => 'Olahraga',     'created_at' => now(), 'updated_at' => now()],
            ['nama_jenis' => 'Seni',         'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('tingkat_prestasi')->insertOrIgnore([
            ['nama_tingkat' => 'Kampus',        'created_at' => now(), 'updated_at' => now()],
            ['nama_tingkat' => 'Kota',          'created_at' => now(), 'updated_at' => now()],
            ['nama_tingkat' => 'Provinsi',      'created_at' => now(), 'updated_at' => now()],
            ['nama_tingkat' => 'Nasional',      'created_at' => now(), 'updated_at' => now()],
            ['nama_tingkat' => 'Internasional', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
