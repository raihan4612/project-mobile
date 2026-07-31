<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HakAksesSeeder extends Seeder
{
    /**
     * Seed 4 level hak akses:
     *   Level 1 – Admin      : akses penuh ke seluruh fitur
     *   Level 2 – Petugas    : akses operasional (buku & peminjaman), tidak bisa hapus
     *   Level 3 – Mahasiswa  : hanya baca + tambah prestasi sendiri
     *   Level 4 – Guest      : hanya baca, tidak bisa ubah data apapun
     */
    public function run(): void
    {
        // Truncate agar tidak duplikat saat re-seed
        DB::table('hak_akses')->truncate();

        $roles = [
            // ─── Level 1: admin ──────────────────────────────────────────────
            [
                'nama_role'  => 'admin',
                'level'      => 1,
                'deskripsi'  => 'Akses penuh: kelola seluruh data, user, hak akses, dan laporan.',
                'can_create' => true,
                'can_read'   => true,
                'can_update' => true,
                'can_delete' => true,
                'can_export' => true,
                'is_active'  => true,
            ],

            // ─── Level 2: petugas ────────────────────────────────────────────
            [
                'nama_role'  => 'petugas',
                'level'      => 2,
                'deskripsi'  => 'Akses operasional: input & edit buku, catat peminjaman/pengembalian. Tidak bisa hapus.',
                'can_create' => true,
                'can_read'   => true,
                'can_update' => true,
                'can_delete' => false,
                'can_export' => true,
                'is_active'  => true,
            ],

            // ─── Level 3: user (mahasiswa) ───────────────────────────────────
            [
                'nama_role'  => 'user',
                'level'      => 3,
                'deskripsi'  => 'Akses terbatas: lihat data, tambah & edit prestasi sendiri. Tidak bisa hapus.',
                'can_create' => true,
                'can_read'   => true,
                'can_update' => true,
                'can_delete' => false,
                'can_export' => false,
                'is_active'  => true,
            ],

            // ─── Level 4: guest ──────────────────────────────────────────────
            [
                'nama_role'  => 'guest',
                'level'      => 4,
                'deskripsi'  => 'Hanya baca: melihat data mahasiswa, buku, dan prestasi. Tidak ada aksi ubah data.',
                'can_create' => false,
                'can_read'   => true,
                'can_update' => false,
                'can_delete' => false,
                'can_export' => false,
                'is_active'  => true,
            ],
        ];

        foreach ($roles as $role) {
            DB::table('hak_akses')->insert(array_merge($role, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        $this->command->info('✅  HakAksesSeeder: 4 level berhasil di-seed.');
        $this->command->table(
            ['Level', 'Role', 'Create', 'Read', 'Update', 'Delete', 'Export'],
            collect($roles)->map(fn($r) => [
                $r['level'],
                $r['nama_role'],
                $r['can_create'] ? '✓' : '–',
                $r['can_read']   ? '✓' : '–',
                $r['can_update'] ? '✓' : '–',
                $r['can_delete'] ? '✓' : '–',
                $r['can_export'] ? '✓' : '–',
            ])->toArray()
        );
    }
}