<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProgramBeasiswaResource;
use App\Models\ProgramBeasiswa;
use Illuminate\Http\Request;

class ProgramBeasiswaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $program = ProgramBeasiswa::withCount('beasiswa')
            ->when($search, fn ($q, $s) => $q->where('nama_beasiswa', 'like', "%{$s}%"))
            ->latest()
            ->paginate(20);

        return ProgramBeasiswaResource::collection($program);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_beasiswa'  => 'required|string|max:255',
            'penyelenggara'  => 'required|string|max:255',
            'tahun_akademik' => 'required|string|max:20',
            'jumlah_dana'    => 'required|numeric|min:0',
        ]);

        $program = ProgramBeasiswa::create($data);
        $program->loadCount('beasiswa');

        return new ProgramBeasiswaResource($program);
    }

    public function show(ProgramBeasiswa $programBeasiswa)
    {
        $programBeasiswa->loadCount('beasiswa');

        return new ProgramBeasiswaResource($programBeasiswa);
    }

    public function update(Request $request, ProgramBeasiswa $programBeasiswa)
    {
        $data = $request->validate([
            'nama_beasiswa'  => 'sometimes|required|string|max:255',
            'penyelenggara'  => 'sometimes|required|string|max:255',
            'tahun_akademik' => 'sometimes|required|string|max:20',
            'jumlah_dana'    => 'sometimes|required|numeric|min:0',
        ]);

        $programBeasiswa->update($data);
        $programBeasiswa->loadCount('beasiswa');

        return new ProgramBeasiswaResource($programBeasiswa);
    }

    public function destroy(ProgramBeasiswa $programBeasiswa)
    {
        if ($programBeasiswa->beasiswa()->exists()) {
            return response()->json(['message' => 'Program beasiswa tidak dapat dihapus karena masih memiliki pengajuan'], 409);
        }

        $programBeasiswa->delete();

        return response()->json(['message' => 'Program beasiswa berhasil dihapus']);
    }
}
