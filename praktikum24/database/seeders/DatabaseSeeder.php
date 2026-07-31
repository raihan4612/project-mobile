<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            HakAksesSeeder::class,
            UserSeeder::class,
            MahasiswaUserSeeder::class,
            PrestasiSeeder::class,
            IpkSeeder::class,
            ProgramBeasiswaSeeder::class,
            PrestasiMahasiswaSeeder::class,
            BeasiswaSeeder::class,
            PeminjamanSeeder::class,
        ]);
    }
}
