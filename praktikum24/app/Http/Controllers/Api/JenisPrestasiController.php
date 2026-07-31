<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreJenisPrestasiRequest;
use App\Http\Requests\UpdateJenisPrestasiRequest;
use App\Http\Resources\JenisPrestasiResource;
use App\Models\JenisPrestasi;

class JenisPrestasiController extends Controller
{
    public function index()
    {
        return JenisPrestasiResource::collection(JenisPrestasi::withCount('prestasi')->latest()->paginate(20));
    }

    public function store(StoreJenisPrestasiRequest $request)
    {
        $jenis = JenisPrestasi::create($request->validated());

        return new JenisPrestasiResource($jenis);
    }

    public function show(JenisPrestasi $jenisPrestasi)
    {
        return new JenisPrestasiResource($jenisPrestasi);
    }

    public function update(UpdateJenisPrestasiRequest $request, JenisPrestasi $jenisPrestasi)
    {
        $jenisPrestasi->update($request->validated());

        return new JenisPrestasiResource($jenisPrestasi);
    }

    public function destroy(JenisPrestasi $jenisPrestasi)
    {
        if ($jenisPrestasi->prestasi()->exists()) {
            return response()->json(['message' => 'Tidak dapat menghapus jenis prestasi yang masih digunakan'], 409);
        }

        $jenisPrestasi->delete();

        return response()->json(['message' => 'Jenis prestasi berhasil dihapus']);
    }
}
