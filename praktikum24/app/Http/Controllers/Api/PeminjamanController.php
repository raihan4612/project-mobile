<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePeminjamanRequest;
use App\Http\Requests\UpdatePeminjamanRequest;
use App\Http\Resources\PeminjamanResource;
use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $peminjaman = Peminjaman::with(['mahasiswa', 'buku', 'petugas'])
            ->when($request->user()->role === 'user', function ($q) use ($request) {
                return $q->where('mahasiswa_id', $request->user()->mahasiswa_id);
            })
            ->latest()
            ->paginate(20);

        return PeminjamanResource::collection($peminjaman);
    }

    public function store(StorePeminjamanRequest $request)
    {
        $data = $request->validated();

        if ($request->user()->role === 'user') {
            $data['mahasiswa_id'] = $request->user()->mahasiswa_id;
        }

        $data['kode_peminjaman'] = 'PJM-' . now()->format('Ymd') . '-' . str_pad(Peminjaman::whereDate('created_at', today())->count() + 1, 3, '0', STR_PAD_LEFT);
        $data['status'] = 'Dipinjam';
        $data['denda'] = 0;

        $buku = Buku::findOrFail($data['buku_id']);
        if ($buku->jumlah_tersedia < 1) {
            return response()->json(['message' => 'Stok buku tidak tersedia'], 409);
        }
        $buku->decrement('jumlah_tersedia');

        $peminjaman = Peminjaman::create($data);
        $peminjaman->load(['mahasiswa', 'buku', 'petugas']);

        return new PeminjamanResource($peminjaman);
    }

    public function show(Request $request, Peminjaman $peminjaman)
    {
        if ($request->user()->role === 'user' && $peminjaman->mahasiswa_id !== $request->user()->mahasiswa_id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $peminjaman->load(['mahasiswa', 'buku', 'petugas']);

        return new PeminjamanResource($peminjaman);
    }

    public function update(Request $request, Peminjaman $peminjaman)
    {
        if ($request->user()->role === 'user') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $peminjaman->update($request->validated());
        $peminjaman->load(['mahasiswa', 'buku', 'petugas']);

        return new PeminjamanResource($peminjaman);
    }

    public function destroy(Request $request, Peminjaman $peminjaman)
    {
        if ($request->user()->role === 'user') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        if ($peminjaman->status === 'Dipinjam') {
            return response()->json(['message' => 'Tidak dapat menghapus peminjaman yang masih aktif'], 409);
        }

        $peminjaman->delete();

        return response()->json(['message' => 'Peminjaman berhasil dihapus']);
    }

    public function pengembalian(Request $request, Peminjaman $peminjaman)
    {
        if ($request->user()->role === 'user' && $peminjaman->mahasiswa_id !== $request->user()->mahasiswa_id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        if ($peminjaman->status !== 'Dipinjam') {
            return response()->json(['message' => 'Peminjaman ini sudah dikembalikan'], 409);
        }

        $terlambat = now()->startOfDay()->gt($peminjaman->tanggal_kembali_rencana);
        $denda = $terlambat
            ? now()->startOfDay()->diffInDays($peminjaman->tanggal_kembali_rencana) * 1000
            : 0;

        $peminjaman->update([
            'tanggal_kembali_aktual' => now(),
            'status'                 => $terlambat ? 'Terlambat' : 'Dikembalikan',
            'denda'                  => $denda,
        ]);

        $peminjaman->buku->increment('jumlah_tersedia');
        $peminjaman->load(['mahasiswa', 'buku', 'petugas']);

        return new PeminjamanResource($peminjaman);
    }
}
