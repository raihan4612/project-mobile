<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Mahasiswa;
use App\Models\User;

class MahasiswaUserSeeder extends Seeder
{
    public function run()
    {
        $mahasiswa = Mahasiswa::all();
        $created = 0;

        foreach ($mahasiswa as $mhs) {
            User::updateOrCreate(
                ['nim' => $mhs->nim],
                [
                    'nama'         => $mhs->nama,
                    'email'        => $mhs->email,
                    'password'     => Hash::make('password'),
                    'role'         => 'user',
                    'mahasiswa_id' => $mhs->id,
                ]
            );
            $created++;
        }

        $this->command->info("Berhasil membuat/update {$created} akun mahasiswa.");
    }
}
