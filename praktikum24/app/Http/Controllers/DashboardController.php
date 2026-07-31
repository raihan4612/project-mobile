<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\Prestasi;
use App\Models\Beasiswa;
use App\Models\ProgramBeasiswa;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalMahasiswa  = Mahasiswa::count();
        $totalBuku       = Buku::count();
        $totalPeminjaman = Peminjaman::count();
        $totalPrestasi   = Prestasi::count();

        $peminjamanDipinjam     = Peminjaman::where('status', 'Dipinjam')->count();
        $peminjamanDikembalikan = Peminjaman::where('status', 'Dikembalikan')->count();
        $peminjamanTerlambat    = Peminjaman::where('status', 'Terlambat')->count();

        $peminjaman7Hari = Peminjaman::selectRaw('DATE(tanggal_pinjam) as tgl, count(*) as total')
            ->where('tanggal_pinjam', '>=', now()->subDays(6))
            ->groupBy('tgl')
            ->orderBy('tgl')
            ->pluck('total', 'tgl');

        $chartPeminjamanLabels = [];
        $chartPeminjamanData   = [];
        for ($i = 6; $i >= 0; $i--) {
            $tgl = now()->subDays($i)->format('Y-m-d');
            $chartPeminjamanLabels[] = now()->subDays($i)->format('d/m');
            $chartPeminjamanData[] = $peminjaman7Hari[$tgl] ?? 0;
        }

        $prestasiTerbaru = Prestasi::with('mahasiswa')
            ->latest()->take(5)->get();

        $peminjamanTerbaru = Peminjaman::with('mahasiswa')
            ->latest()->take(5)->get();

        $totalProgramBeasiswa = ProgramBeasiswa::count();
        $totalBeasiswa        = Beasiswa::count();

        return view('dashboard', compact(
            'totalMahasiswa', 'totalBuku', 'totalPeminjaman', 'totalPrestasi',
            'peminjamanDipinjam', 'peminjamanDikembalikan', 'peminjamanTerlambat',
            'chartPeminjamanLabels', 'chartPeminjamanData',
            'prestasiTerbaru', 'peminjamanTerbaru',
            'totalProgramBeasiswa', 'totalBeasiswa'
        ));
    }
}
