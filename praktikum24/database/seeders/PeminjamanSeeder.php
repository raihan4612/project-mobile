<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PeminjamanSeeder extends Seeder
{
    public function run(): void
    {
        $mahasiswaIds = DB::table('mhs')->pluck('id');
        $bukuIds      = DB::table('buku')->pluck('id');
        $petugasIds   = DB::table('petugas')->pluck('id');
        $now          = now();

        $usedBorrow = [];

        $data = [
            // 10 records — Dikembalikan (tepat waktu)
            [
                'mahasiswa_id' => 2,  'buku_id' => 1,  'petugas_id' => 1,
                'pinjam' => '2026-06-01', 'kembali_rencana' => '2026-06-08', 'kembali_aktual' => '2026-06-07',
                'status' => 'Dikembalikan', 'denda' => 0, 'catatan' => null,
            ],
            [
                'mahasiswa_id' => 3,  'buku_id' => 3,  'petugas_id' => 2,
                'pinjam' => '2026-06-03', 'kembali_rencana' => '2026-06-10', 'kembali_aktual' => '2026-06-10',
                'status' => 'Dikembalikan', 'denda' => 0, 'catatan' => null,
            ],
            [
                'mahasiswa_id' => 4,  'buku_id' => 5,  'petugas_id' => 3,
                'pinjam' => '2026-06-05', 'kembali_rencana' => '2026-06-12', 'kembali_aktual' => '2026-06-11',
                'status' => 'Dikembalikan', 'denda' => 0, 'catatan' => 'Buku dalam kondisi baik',
            ],
            [
                'mahasiswa_id' => 5,  'buku_id' => 7,  'petugas_id' => 4,
                'pinjam' => '2026-06-07', 'kembali_rencana' => '2026-06-14', 'kembali_aktual' => '2026-06-13',
                'status' => 'Dikembalikan', 'denda' => 0, 'catatan' => null,
            ],
            [
                'mahasiswa_id' => 6,  'buku_id' => 10, 'petugas_id' => 5,
                'pinjam' => '2026-06-10', 'kembali_rencana' => '2026-06-17', 'kembali_aktual' => '2026-06-16',
                'status' => 'Dikembalikan', 'denda' => 0, 'catatan' => null,
            ],
            [
                'mahasiswa_id' => 7,  'buku_id' => 13, 'petugas_id' => 6,
                'pinjam' => '2026-06-12', 'kembali_rencana' => '2026-06-19', 'kembali_aktual' => '2026-06-18',
                'status' => 'Dikembalikan', 'denda' => 0, 'catatan' => null,
            ],
            [
                'mahasiswa_id' => 8,  'buku_id' => 14, 'petugas_id' => 7,
                'pinjam' => '2026-06-15', 'kembali_rencana' => '2026-06-22', 'kembali_aktual' => '2026-06-21',
                'status' => 'Dikembalikan', 'denda' => 0, 'catatan' => 'Perpanjang 1 hari',
            ],
            [
                'mahasiswa_id' => 9,  'buku_id' => 16, 'petugas_id' => 8,
                'pinjam' => '2026-06-18', 'kembali_rencana' => '2026-06-25', 'kembali_aktual' => '2026-06-24',
                'status' => 'Dikembalikan', 'denda' => 0, 'catatan' => null,
            ],
            [
                'mahasiswa_id' => 10, 'buku_id' => 17, 'petugas_id' => 9,
                'pinjam' => '2026-06-20', 'kembali_rencana' => '2026-06-27', 'kembali_aktual' => '2026-06-26',
                'status' => 'Dikembalikan', 'denda' => 0, 'catatan' => null,
            ],
            [
                'mahasiswa_id' => 11, 'buku_id' => 19, 'petugas_id' => 10,
                'pinjam' => '2026-06-22', 'kembali_rencana' => '2026-06-29', 'kembali_aktual' => '2026-06-28',
                'status' => 'Dikembalikan', 'denda' => 0, 'catatan' => null,
            ],

            // 5 records — Terlambat
            [
                'mahasiswa_id' => 12, 'buku_id' => 2,  'petugas_id' => 11,
                'pinjam' => '2026-06-01', 'kembali_rencana' => '2026-06-08', 'kembali_aktual' => '2026-06-12',
                'status' => 'Terlambat', 'denda' => 4000, 'catatan' => 'Terlambat 4 hari',
            ],
            [
                'mahasiswa_id' => 13, 'buku_id' => 4,  'petugas_id' => 12,
                'pinjam' => '2026-06-05', 'kembali_rencana' => '2026-06-12', 'kembali_aktual' => '2026-06-20',
                'status' => 'Terlambat', 'denda' => 8000, 'catatan' => 'Terlambat 8 hari',
            ],
            [
                'mahasiswa_id' => 14, 'buku_id' => 6,  'petugas_id' => 13,
                'pinjam' => '2026-06-10', 'kembali_rencana' => '2026-06-17', 'kembali_aktual' => '2026-06-19',
                'status' => 'Terlambat', 'denda' => 2000, 'catatan' => 'Terlambat 2 hari',
            ],
            [
                'mahasiswa_id' => 15, 'buku_id' => 8,  'petugas_id' => 14,
                'pinjam' => '2026-06-15', 'kembali_rencana' => '2026-06-22', 'kembali_aktual' => '2026-07-01',
                'status' => 'Terlambat', 'denda' => 9000, 'catatan' => 'Terlambat 9 hari',
            ],
            [
                'mahasiswa_id' => 16, 'buku_id' => 9,  'petugas_id' => 15,
                'pinjam' => '2026-06-20', 'kembali_rencana' => '2026-06-27', 'kembali_aktual' => '2026-07-05',
                'status' => 'Terlambat', 'denda' => 8000, 'catatan' => 'Terlambat 8 hari, buku sedikit rusak',
            ],

            // 10 records — Masih Dipinjam (belum dikembalikan)
            [
                'mahasiswa_id' => 17, 'buku_id' => 11, 'petugas_id' => 16,
                'pinjam' => '2026-07-01', 'kembali_rencana' => '2026-07-08', 'kembali_aktual' => null,
                'status' => 'Dipinjam', 'denda' => 0, 'catatan' => null,
            ],
            [
                'mahasiswa_id' => 18, 'buku_id' => 12, 'petugas_id' => 17,
                'pinjam' => '2026-07-03', 'kembali_rencana' => '2026-07-10', 'kembali_aktual' => null,
                'status' => 'Dipinjam', 'denda' => 0, 'catatan' => null,
            ],
            [
                'mahasiswa_id' => 19, 'buku_id' => 15, 'petugas_id' => 18,
                'pinjam' => '2026-07-05', 'kembali_rencana' => '2026-07-12', 'kembali_aktual' => null,
                'status' => 'Dipinjam', 'denda' => 0, 'catatan' => null,
            ],
            [
                'mahasiswa_id' => 20, 'buku_id' => 18, 'petugas_id' => 19,
                'pinjam' => '2026-07-07', 'kembali_rencana' => '2026-07-14', 'kembali_aktual' => null,
                'status' => 'Dipinjam', 'denda' => 0, 'catatan' => null,
            ],
            [
                'mahasiswa_id' => 21, 'buku_id' => 20, 'petugas_id' => 20,
                'pinjam' => '2026-07-10', 'kembali_rencana' => '2026-07-17', 'kembali_aktual' => null,
                'status' => 'Dipinjam', 'denda' => 0, 'catatan' => null,
            ],
            [
                'mahasiswa_id' => 22, 'buku_id' => 1,  'petugas_id' => 1,
                'pinjam' => '2026-07-12', 'kembali_rencana' => '2026-07-19', 'kembali_aktual' => null,
                'status' => 'Dipinjam', 'denda' => 0, 'catatan' => null,
            ],
            [
                'mahasiswa_id' => 23, 'buku_id' => 3,  'petugas_id' => 2,
                'pinjam' => '2026-07-14', 'kembali_rencana' => '2026-07-21', 'kembali_aktual' => null,
                'status' => 'Dipinjam', 'denda' => 0, 'catatan' => null,
            ],
            [
                'mahasiswa_id' => 24, 'buku_id' => 5,  'petugas_id' => 3,
                'pinjam' => '2026-07-15', 'kembali_rencana' => '2026-07-22', 'kembali_aktual' => null,
                'status' => 'Dipinjam', 'denda' => 0, 'catatan' => null,
            ],
            [
                'mahasiswa_id' => 25, 'buku_id' => 7,  'petugas_id' => 4,
                'pinjam' => '2026-07-17', 'kembali_rencana' => '2026-07-24', 'kembali_aktual' => null,
                'status' => 'Dipinjam', 'denda' => 0, 'catatan' => null,
            ],
            [
                'mahasiswa_id' => 26, 'buku_id' => 10, 'petugas_id' => 5,
                'pinjam' => '2026-07-20', 'kembali_rencana' => '2026-07-27', 'kembali_aktual' => null,
                'status' => 'Dipinjam', 'denda' => 0, 'catatan' => null,
            ],
        ];

        $kodeCounter = 1;
        $peminjamanData = [];

        foreach ($data as $item) {
            $tanggalPinjam = \Carbon\Carbon::parse($item['pinjam']);
            $kode = 'PJM-' . $tanggalPinjam->format('Ymd') . '-' . str_pad($kodeCounter, 3, '0', STR_PAD_LEFT);
            $kodeCounter++;

            $peminjamanData[] = [
                'kode_peminjaman'         => $kode,
                'mahasiswa_id'            => $item['mahasiswa_id'],
                'buku_id'                 => $item['buku_id'],
                'petugas_id'              => $item['petugas_id'],
                'tanggal_pinjam'          => $item['pinjam'],
                'tanggal_kembali_rencana' => $item['kembali_rencana'],
                'tanggal_kembali_aktual'  => $item['kembali_aktual'],
                'status'                  => $item['status'],
                'denda'                   => $item['denda'],
                'catatan'                 => $item['catatan'],
                'created_at'              => $now,
                'updated_at'              => $now,
            ];
        }

        DB::table('peminjaman')->insert($peminjamanData);

        // Update jumlah_tersedia buku untuk yang sudah dikembalikan/terlambat
        $returnedBukuIds = [1, 3, 5, 7, 10, 13, 14, 16, 17, 19, 2, 4, 6, 8, 9];
        foreach ($returnedBukuIds as $bukuId) {
            DB::table('buku')->where('id', $bukuId)->increment('jumlah_tersedia');
        }

        // Update jumlah_tersedia buku untuk yang masih dipinjam (decrement)
        $borrowedBukuIds = [11, 12, 15, 18, 20, 1, 3, 5, 7, 10];
        foreach ($borrowedBukuIds as $bukuId) {
            DB::table('buku')->where('id', $bukuId)->decrement('jumlah_tersedia');
        }

        $this->command->info('Berhasil membuat ' . count($peminjamanData) . ' data peminjaman.');
    }
}
