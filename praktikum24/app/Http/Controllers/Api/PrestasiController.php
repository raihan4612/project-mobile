<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePrestasiRequest;
use App\Http\Requests\UpdatePrestasiRequest;
use App\Http\Resources\PrestasiResource;
use App\Models\Prestasi;
use Illuminate\Http\Request;

class PrestasiController extends Controller
{
    public function index(Request $request)
    {
        $prestasi = Prestasi::with(['mahasiswa', 'jenis', 'tingkat', 'verifikasi.admin'])
            ->when($request->user()->role === 'user', function ($q) use ($request) {
                return $q->where('mahasiswa_id', $request->user()->mahasiswa_id);
            })
            ->latest()
            ->paginate(20);

        return PrestasiResource::collection($prestasi);
    }

    public function store(StorePrestasiRequest $request)
    {
        $data = $request->validated();

        if ($request->user()->role === 'user') {
            $data['mahasiswa_id'] = $request->user()->mahasiswa_id;
        }

        if ($request->hasFile('sertifikat')) {
            $data['sertifikat'] = $request->file('sertifikat')->store('sertifikat', 'public');
        }

        $data['status_verifikasi'] = 'Pending';
        $prestasi = Prestasi::create($data);
        $prestasi->load(['mahasiswa', 'jenis', 'tingkat']);

        return new PrestasiResource($prestasi);
    }

    public function show(Request $request, Prestasi $prestasi)
    {
        if ($request->user()->role === 'user' && $prestasi->mahasiswa_id !== $request->user()->mahasiswa_id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $prestasi->load(['mahasiswa', 'jenis', 'tingkat', 'verifikasi.admin']);

        return new PrestasiResource($prestasi);
    }

    public function update(UpdatePrestasiRequest $request, Prestasi $prestasi)
    {
        if ($request->user()->role === 'user' && $prestasi->mahasiswa_id !== $request->user()->mahasiswa_id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $data = $request->validated();

        if ($request->user()->role === 'user') {
            $data['mahasiswa_id'] = $request->user()->mahasiswa_id;
        }

        if ($request->hasFile('sertifikat')) {
            if ($prestasi->sertifikat) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($prestasi->sertifikat);
            }
            $data['sertifikat'] = $request->file('sertifikat')->store('sertifikat', 'public');
        }

        $prestasi->update($data);
        $prestasi->load(['mahasiswa', 'jenis', 'tingkat']);

        return new PrestasiResource($prestasi);
    }

    public function destroy(Request $request, Prestasi $prestasi)
    {
        if ($request->user()->role === 'user' && $prestasi->mahasiswa_id !== $request->user()->mahasiswa_id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        if ($prestasi->sertifikat) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($prestasi->sertifikat);
        }

        $prestasi->delete();

        return response()->json(['message' => 'Prestasi berhasil dihapus']);
    }

    public function verifikasi(Request $request, Prestasi $prestasi)
    {
        $data = $request->validate([
            'status_verifikasi' => 'required|in:Disetujui,Ditolak',
            'catatan'           => 'nullable|string',
        ]);

        $prestasi->update(['status_verifikasi' => $data['status_verifikasi']]);

        $prestasi->verifikasi()->updateOrCreate(
            ['prestasi_id' => $prestasi->id],
            [
                'admin_id'           => $request->user()->id,
                'tanggal_verifikasi' => now(),
                'catatan'            => $data['catatan'] ?? null,
            ]
        );

        $prestasi->load(['mahasiswa', 'jenis', 'tingkat', 'verifikasi.admin']);

        return new PrestasiResource($prestasi);
    }
}
