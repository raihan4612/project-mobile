<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IpkSeeder extends Seeder
{
    public function run(): void
    {
        $mahasiswa = DB::table('mhs')->whereNull('ipk')->get();

        foreach ($mahasiswa as $mhs) {
            $ipk = round(mt_rand(250, 395) / 100, 2);
            DB::table('mhs')->where('id', $mhs->id)->update(['ipk' => $ipk]);
        }

        $this->command->info('IPK berhasil diisi untuk ' . $mahasiswa->count() . ' mahasiswa.');
    }
}
