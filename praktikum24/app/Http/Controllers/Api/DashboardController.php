<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MahasiswaResource;
use App\Http\Resources\PrestasiResource;
use App\Models\Beasiswa;
use App\Models\Buku;
use App\Models\Mahasiswa;
use App\Models\Peminjaman;
use App\Models\Prestasi;
use App\Models\ProgramBeasiswa;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $isUser = $request->user()->role === 'user';
        $mahasiswaId = $request->user()->mahasiswa_id;

        $totalMahasiswa  = Mahasiswa::count();
        $totalBuku       = Buku::count();
        $totalPeminjaman = Peminjaman::count();
        $totalPrestasi   = Prestasi::count();
        $totalBeasiswa   = Beasiswa::count();
        $totalProgramBeasiswa = ProgramBeasiswa::count();

        if ($isUser) {
            $totalPeminjaman = Peminjaman::where('mahasiswa_id', $mahasiswaId)->count();
            $totalPrestasi   = Prestasi::where('mahasiswa_id', $mahasiswaId)->count();
            $totalBeasiswa   = Beasiswa::where('mahasiswa_id', $mahasiswaId)->count();
        }

        $peminjamanDipinjam     = Peminjaman::where('status', 'Dipinjam');
        $peminjamanDikembalikan = Peminjaman::where('status', 'Dikembalikan');
        $peminjamanTerlambat    = Peminjaman::where('status', 'Terlambat');

        $prestasiPending  = Prestasi::where('status_verifikasi', 'Pending');
        $prestasiDisetujui = Prestasi::where('status_verifikasi', 'Disetujui');
        $prestasiDitolak  = Prestasi::where('status_verifikasi', 'Ditolak');

        $beasiswaDiajukan  = Beasiswa::where('status', 'Diajukan');
        $beasiswaDisetujui = Beasiswa::where('status', 'Disetujui');
        $beasiswaDitolak   = Beasiswa::where('status', 'Ditolak');

        if ($isUser) {
            $peminjamanDipinjam->where('mahasiswa_id', $mahasiswaId);
            $peminjamanDikembalikan->where('mahasiswa_id', $mahasiswaId);
            $peminjamanTerlambat->where('mahasiswa_id', $mahasiswaId);
            $prestasiPending->where('mahasiswa_id', $mahasiswaId);
            $prestasiDisetujui->where('mahasiswa_id', $mahasiswaId);
            $prestasiDitolak->where('mahasiswa_id', $mahasiswaId);
            $beasiswaDiajukan->where('mahasiswa_id', $mahasiswaId);
            $beasiswaDisetujui->where('mahasiswa_id', $mahasiswaId);
            $beasiswaDitolak->where('mahasiswa_id', $mahasiswaId);
        }

        $peminjamanTerbaruQuery = Peminjaman::with('mahasiswa', 'buku', 'petugas');
        $prestasiTerbaruQuery = Prestasi::with('mahasiswa');
        if ($isUser) {
            $peminjamanTerbaruQuery->where('mahasiswa_id', $mahasiswaId);
            $prestasiTerbaruQuery->where('mahasiswa_id', $mahasiswaId);
        }

        $prestasiTerbaru = PrestasiResource::collection(
            $prestasiTerbaruQuery->latest()->take(5)->get()
        );

        $peminjamanTerbaru = \App\Http\Resources\PeminjamanResource::collection(
            $peminjamanTerbaruQuery->latest()->take(5)->get()
        );

        return response()->json([
            'statistik' => [
                'mahasiswa'            => $totalMahasiswa,
                'buku'                 => $totalBuku,
                'peminjaman'           => $totalPeminjaman,
                'prestasi'             => $totalPrestasi,
                'beasiswa'             => $totalBeasiswa,
                'program_beasiswa'     => $totalProgramBeasiswa,
            ],
            'peminjaman_status' => [
                'dipinjam'     => $peminjamanDipinjam->count(),
                'dikembalikan' => $peminjamanDikembalikan->count(),
                'terlambat'    => $peminjamanTerlambat->count(),
            ],
            'prestasi_status' => [
                'pending'   => $prestasiPending->count(),
                'disetujui' => $prestasiDisetujui->count(),
                'ditolak'   => $prestasiDitolak->count(),
            ],
            'beasiswa_status' => [
                'diajukan'  => $beasiswaDiajukan->count(),
                'disetujui' => $beasiswaDisetujui->count(),
                'ditolak'   => $beasiswaDitolak->count(),
            ],
            'prestasi_terbaru'   => $prestasiTerbaru,
            'peminjaman_terbaru' => $peminjamanTerbaru,
        ]);
    }
}
