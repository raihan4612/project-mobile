<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTingkatPrestasiRequest;
use App\Http\Requests\UpdateTingkatPrestasiRequest;
use App\Http\Resources\TingkatPrestasiResource;
use App\Models\TingkatPrestasi;

class TingkatPrestasiController extends Controller
{
    public function index()
    {
        return TingkatPrestasiResource::collection(TingkatPrestasi::withCount('prestasi')->latest()->paginate(20));
    }

    public function store(StoreTingkatPrestasiRequest $request)
    {
        $tingkat = TingkatPrestasi::create($request->validated());

        return new TingkatPrestasiResource($tingkat);
    }

    public function show(TingkatPrestasi $tingkatPrestasi)
    {
        return new TingkatPrestasiResource($tingkatPrestasi);
    }

    public function update(UpdateTingkatPrestasiRequest $request, TingkatPrestasi $tingkatPrestasi)
    {
        $tingkatPrestasi->update($request->validated());

        return new TingkatPrestasiResource($tingkatPrestasi);
    }

    public function destroy(TingkatPrestasi $tingkatPrestasi)
    {
        if ($tingkatPrestasi->prestasi()->exists()) {
            return response()->json(['message' => 'Tidak dapat menghapus tingkat prestasi yang masih digunakan'], 409);
        }

        $tingkatPrestasi->delete();

        return response()->json(['message' => 'Tingkat prestasi berhasil dihapus']);
    }
}
