<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBukuRequest;
use App\Http\Requests\UpdateBukuRequest;
use App\Http\Resources\BukuResource;
use App\Models\Buku;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    public function index()
    {
        return BukuResource::collection(Buku::withCount('peminjaman')->latest()->paginate(20));
    }

    public function store(Request $request)
    {
        if ($request->user()->role === 'user') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $data = $request->validate((new StoreBukuRequest)->rules());
        $data['jumlah_tersedia'] = $data['jumlah_stok'];
        $data['status'] ??= $data['jumlah_stok'] > 0 ? 'Tersedia' : 'Habis';

        $buku = Buku::create($data);

        return new BukuResource($buku);
    }

    public function show(Buku $buku)
    {
        $buku->loadCount('peminjaman');

        return new BukuResource($buku);
    }

    public function update(Request $request, Buku $buku)
    {
        if ($request->user()->role === 'user') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $buku->update($request->validate((new UpdateBukuRequest)->rules()));

        return new BukuResource($buku);
    }

    public function destroy(Request $request, Buku $buku)
    {
        if ($request->user()->role === 'user') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        if ($buku->peminjaman()->where('status', 'Dipinjam')->exists()) {
            return response()->json(['message' => 'Tidak dapat menghapus buku yang masih dipinjam'], 409);
        }

        $buku->delete();

        return response()->json(['message' => 'Buku berhasil dihapus']);
    }
}
