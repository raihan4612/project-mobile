<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate(
            ['email' => 'admin@simak.com'],
            [
                'nama'     => 'Administrator',
                'password' => Hash::make('admin123'),
                'role'     => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'petugas@simak.com'],
            [
                'nama'     => 'Petugas',
                'password' => Hash::make('petugas123'),
                'role'     => 'petugas',
            ]
        );

        // Akun mahasiswa dibuat otomatis dari tabel mhs
        // oleh MahasiswaUserSeeder (login pakai NIM, password: password)
    }
}
