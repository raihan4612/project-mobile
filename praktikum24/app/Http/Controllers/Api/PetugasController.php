<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePetugasRequest;
use App\Http\Requests\UpdatePetugasRequest;
use App\Http\Resources\PetugasResource;
use App\Models\Petugas;

class PetugasController extends Controller
{
    public function index()
    {
        return PetugasResource::collection(Petugas::withCount('peminjaman')->latest()->paginate(20));
    }

    public function store(StorePetugasRequest $request)
    {
        $data = $request->validated();
        $data['kode_petugas'] ??= 'PTG-' . str_pad(Petugas::max('id') + 1 ?? 1, 3, '0', STR_PAD_LEFT);

        $petugas = Petugas::create($data);

        return new PetugasResource($petugas);
    }

    public function show(Petugas $petugas)
    {
        $petugas->loadCount('peminjaman');

        return new PetugasResource($petugas);
    }

    public function update(UpdatePetugasRequest $request, Petugas $petugas)
    {
        $petugas->update($request->validated());

        return new PetugasResource($petugas);
    }

    public function destroy(Petugas $petugas)
    {
        if ($petugas->peminjaman()->where('status', 'Dipinjam')->exists()) {
            return response()->json(['message' => 'Tidak dapat menghapus petugas yang masih memiliki peminjaman aktif'], 409);
        }

        $petugas->delete();

        return response()->json(['message' => 'Petugas berhasil dihapus']);
    }
}
