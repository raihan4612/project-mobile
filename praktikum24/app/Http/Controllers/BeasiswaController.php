<?php

namespace App\Http\Controllers;

use App\Models\Beasiswa;
use App\Models\Mahasiswa;
use App\Models\ProgramBeasiswa;
use App\Models\FuzzyHasil;
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

        $dtBeasiswa = Beasiswa::with('mahasiswa.fuzzyHasil', 'programBeasiswa')
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
            })->when($status, function ($q, $s) {
                return $q->where('status', $s);
            })->when($tahun, function ($q, $t) {
                return $q->whereHas('programBeasiswa', function ($q) use ($t) {
                    $q->where('tahun_akademik', $t);
                });
            })->when($rekomendasi, function ($q, $r) {
                return $q->whereHas('mahasiswa.fuzzyHasil', function ($q) use ($r) {
                    $q->where('hasil_rekomendasi', $r);
                });
            })->latest()->paginate(10)->withQueryString();

        $totalDiajukan  = Beasiswa::where('status', 'Diajukan')->count();
        $totalDisetujui = Beasiswa::where('status', 'Disetujui')->count();
        $totalDitolak   = Beasiswa::where('status', 'Ditolak')->count();
        $rataFuzzy      = FuzzyHasil::avg('nilai_fuzzy');
        $rekomendasi    = FuzzyHasil::selectRaw('hasil_rekomendasi, count(*) as total')
                            ->groupBy('hasil_rekomendasi')->pluck('total', 'hasil_rekomendasi');

        $chartStatusLabels = ['Diajukan', 'Disetujui', 'Ditolak'];
        $chartStatusData   = [$totalDiajukan, $totalDisetujui, $totalDitolak];

        $rekomendasiOrder = ['Tidak Layak', 'Dipertimbangkan', 'Layak', 'Sangat Layak'];
        $chartRekomendasiLabels = $rekomendasiOrder;
        $chartRekomendasiData   = array_map(fn($r) => $rekomendasi[$r] ?? 0, $rekomendasiOrder);

        return view('beasiswa.index', compact(
            'dtBeasiswa', 'totalDiajukan', 'totalDisetujui', 'totalDitolak', 'rataFuzzy',
            'chartStatusLabels', 'chartStatusData',
            'chartRekomendasiLabels', 'chartRekomendasiData'
        ));
    }

    public function create()
    {
        $mahasiswaList      = Mahasiswa::where('status', 'Aktif')->orderBy('nama')->get();
        $programBeasiswaList = ProgramBeasiswa::orderBy('nama_beasiswa')->get();
        return view('beasiswa.create', compact('mahasiswaList', 'programBeasiswaList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'mahasiswa_id'        => 'required|exists:mhs,id',
            'program_beasiswa_id' => 'required|exists:program_beasiswa,id',
            'tanggal_pengajuan'   => 'required|date',
            'keterangan'          => 'nullable|string',
        ]);

        Beasiswa::create($request->except('_token'));
        return redirect()->route('beasiswa.index')->with('success', 'Data pengajuan beasiswa berhasil ditambahkan!');
    }

    public function show($id)
    {
        $beasiswa = Beasiswa::with('mahasiswa.fuzzyHasil', 'programBeasiswa')->findOrFail($id);
        return view('beasiswa.show', compact('beasiswa'));
    }

    public function edit($id)
    {
        $beasiswa            = Beasiswa::findOrFail($id);
        $mahasiswaList       = Mahasiswa::where('status', 'Aktif')->orderBy('nama')->get();
        $programBeasiswaList = ProgramBeasiswa::orderBy('nama_beasiswa')->get();
        return view('beasiswa.edit', compact('beasiswa', 'mahasiswaList', 'programBeasiswaList'));
    }

    public function update(Request $request, $id)
    {
        $beasiswa = Beasiswa::findOrFail($id);

        $request->validate([
            'mahasiswa_id'        => 'required|exists:mhs,id',
            'program_beasiswa_id' => 'required|exists:program_beasiswa,id',
            'tanggal_pengajuan'   => 'required|date',
            'keterangan'          => 'nullable|string',
        ]);

        $beasiswa->update($request->except('_token', '_method'));
        return redirect()->route('beasiswa.index')->with('success', 'Data pengajuan beasiswa berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $beasiswa = Beasiswa::findOrFail($id);
        $beasiswa->delete();
        return redirect()->route('beasiswa.index')->with('success', 'Data pengajuan beasiswa berhasil dihapus!');
    }

    // =========================================================================
    //  SPK FUZZY MAMDANI — Hitung Rekomendasi untuk Semua Mahasiswa Aktif
    // =========================================================================
    public function hitungRekomendasi(FuzzyMamdaniService $fuzzy)
    {
        $tingkatSkorMap = [
            'Kampus'         => 20,
            'Kota'           => 40,
            'Provinsi'       => 60,
            'Nasional'       => 80,
            'Internasional'  => 100,
        ];

        $mahasiswaList = Mahasiswa::where('status', 'Aktif')->with('prestasi.tingkat')->get();
        $totalDiproses = 0;

        foreach ($mahasiswaList as $mhs) {
            if (is_null($mhs->ipk)) continue;

            $maxSkor = 0;
            foreach ($mhs->prestasi as $p) {
                if ($p->tingkat && isset($tingkatSkorMap[$p->tingkat->nama_tingkat])) {
                    $skor = $tingkatSkorMap[$p->tingkat->nama_tingkat];
                    if ($skor > $maxSkor) $maxSkor = $skor;
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

        return redirect()->route('beasiswa.index')
            ->with('success', "Rekomendasi berhasil dihitung untuk {$totalDiproses} mahasiswa!");
    }
}
