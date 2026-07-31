<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BeasiswaResource;
use App\Models\Beasiswa;
use App\Models\FuzzyHasil;
use App\Models\Mahasiswa;
use App\Services\FuzzyMamdaniService;
use Illuminate\Http\Request;

class BeasiswaController extends Controller
{
    public function index(Request $request)
    {
        $search      = $request->input('search');
        $status      = $request->input('status');
        $tahun       = $request->input('tahun_akademik');
        $rekomendasi = $request->input('rekomendasi');

        $beasiswa = Beasiswa::with('mahasiswa.fuzzyHasil', 'programBeasiswa')
            ->when($request->user()->role === 'user', function ($q) use ($request) {
                return $q->where('mahasiswa_id', $request->user()->mahasiswa_id);
            })
            ->when($search, function ($q, $s) {
                return $q->where(function ($q) use ($s) {
                    $q->whereHas('programBeasiswa', function ($q) use ($s) {
                        $q->where('nama_beasiswa', 'like', "%{$s}%")
                          ->orWhere('penyelenggara', 'like', "%{$s}%");
                    })->orWhereHas('mahasiswa', function ($q) use ($s) {
                        $q->where('nama', 'like', "%{$s}%")
                          ->orWhere('nim', 'like', "%{$s}%");
                    });
                });
            })
            ->when($status, fn ($q, $s) => $q->where('status', $s))
            ->when($tahun, fn ($q, $t) => $q->whereHas('programBeasiswa', fn ($q) => $q->where('tahun_akademik', $t)))
            ->when($rekomendasi, fn ($q, $r) => $q->whereHas('mahasiswa.fuzzyHasil', fn ($q) => $q->where('hasil_rekomendasi', $r)))
            ->latest()
            ->paginate(20);

        return BeasiswaResource::collection($beasiswa);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'mahasiswa_id'        => $request->user()->role === 'user' ? 'sometimes' : 'required|exists:mhs,id',
            'program_beasiswa_id' => 'required|exists:program_beasiswa,id',
            'tanggal_pengajuan'   => 'required|date',
            'keterangan'          => 'nullable|string',
        ]);

        if ($request->user()->role === 'user') {
            $data['mahasiswa_id'] = $request->user()->mahasiswa_id;
        }

        $data['status'] = 'Diajukan';

        $beasiswa = Beasiswa::create($data);
        $beasiswa->load('mahasiswa.fuzzyHasil', 'programBeasiswa');

        return new BeasiswaResource($beasiswa);
    }

    public function show(Request $request, Beasiswa $beasiswa)
    {
        if ($request->user()->role === 'user' && $beasiswa->mahasiswa_id !== $request->user()->mahasiswa_id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $beasiswa->load('mahasiswa.fuzzyHasil', 'programBeasiswa');

        return new BeasiswaResource($beasiswa);
    }

    public function update(Request $request, Beasiswa $beasiswa)
    {
        if ($request->user()->role === 'user' && $beasiswa->mahasiswa_id !== $request->user()->mahasiswa_id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $data = $request->validate([
            'mahasiswa_id'        => $request->user()->role === 'user' ? 'sometimes' : 'sometimes|required|exists:mhs,id',
            'program_beasiswa_id' => 'sometimes|required|exists:program_beasiswa,id',
            'status'              => 'sometimes|required|in:Diajukan,Disetujui,Ditolak',
            'tanggal_pengajuan'   => 'sometimes|required|date',
            'keterangan'          => 'nullable|string',
        ]);

        if ($request->user()->role === 'user') {
            $data['mahasiswa_id'] = $request->user()->mahasiswa_id;
            unset($data['status']);
        }

        $beasiswa->update($data);
        $beasiswa->load('mahasiswa.fuzzyHasil', 'programBeasiswa');

        return new BeasiswaResource($beasiswa);
    }

    public function destroy(Request $request, Beasiswa $beasiswa)
    {
        if ($request->user()->role === 'user' && $beasiswa->mahasiswa_id !== $request->user()->mahasiswa_id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $beasiswa->delete();

        return response()->json(['message' => 'Beasiswa berhasil dihapus']);
    }

    public function hitungRekomendasi(FuzzyMamdaniService $fuzzy)
    {
        $tingkatSkorMap = [
            'Kampus'        => 20,
            'Kota'          => 40,
            'Provinsi'      => 60,
            'Nasional'      => 80,
            'Internasional' => 100,
        ];

        $mahasiswaList = Mahasiswa::where('status', 'Aktif')->with('prestasi.tingkat')->get();
        $totalDiproses = 0;

        foreach ($mahasiswaList as $mhs) {
            if (is_null($mhs->ipk)) {
                continue;
            }

            $maxSkor = 0;
            foreach ($mhs->prestasi as $p) {
                if ($p->tingkat && isset($tingkatSkorMap[$p->tingkat->nama_tingkat])) {
                    $skor = $tingkatSkorMap[$p->tingkat->nama_tingkat];
                    if ($skor > $maxSkor) {
                        $maxSkor = $skor;
                    }
                }
            }

            $jumlahPrestasi = $mhs->prestasi->count();
            $hasil = $fuzzy->hitung($mhs->ipk, $maxSkor, $jumlahPrestasi);

            FuzzyHasil::updateOrCreate(
                ['mahasiswa_id' => $mhs->id],
                [
                    'nilai_fuzzy'       => $hasil['nilai_fuzzy'],
                    'hasil_rekomendasi' => $hasil['hasil_rekomendasi'],
                ]
            );

            $totalDiproses++;
        }

        return response()->json([
            'message'       => "Rekomendasi berhasil dihitung untuk {$totalDiproses} mahasiswa",
            'total_diproses' => $totalDiproses,
        ]);
    }
}
