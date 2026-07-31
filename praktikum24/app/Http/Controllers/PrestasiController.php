<?php

namespace App\Http\Controllers;

use App\Models\Prestasi;
use App\Models\JenisPrestasi;
use App\Models\TingkatPrestasi;
use App\Models\VerifikasiPrestasi;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PrestasiController extends Controller
{
    public function index(Request $request)
    {
        $search    = $request->input('search');
        $jenis_id  = $request->input('jenis_id');
        $tingkat_id = $request->input('tingkat_id');
        $status    = $request->input('status');

        $dtPrestasi = Prestasi::with(['mahasiswa', 'jenis', 'tingkat'])
            ->when($search, function ($q, $s) {
                return $q->where(function ($q) use ($s) {
                    $q->where('nama_lomba', 'like', "%{$s}%")
                      ->orWhere('penyelenggara', 'like', "%{$s}%")
                      ->orWhereHas('mahasiswa', function ($q) use ($s) {
                          $q->where('nama', 'like', "%{$s}%")
                            ->orWhere('nim', 'like', "%{$s}%");
                      });
                });
            })->when($jenis_id, function ($q, $j) {
                return $q->where('jenis_id', $j);
            })->when($tingkat_id, function ($q, $t) {
                return $q->where('tingkat_id', $t);
            })->when($status, function ($q, $s) {
                return $q->where('status_verifikasi', $s);
            })->latest()->paginate(10)->withQueryString();

        // Total counts for summary cards
        $totalPending  = Prestasi::where('status_verifikasi', 'Pending')->count();
        $totalDisetujui = Prestasi::where('status_verifikasi', 'Disetujui')->count();
        $totalDitolak  = Prestasi::where('status_verifikasi', 'Ditolak')->count();

        return view('prestasi.index', compact('dtPrestasi', 'totalPending', 'totalDisetujui', 'totalDitolak'));
    }

    public function create()
    {
        $mahasiswaList   = Mahasiswa::where('status', 'Aktif')->orderBy('nama')->get();
        $jenisList       = JenisPrestasi::orderBy('nama_jenis')->get();
        $tingkatList     = TingkatPrestasi::orderBy('id')->get();
        return view('prestasi.create', compact('mahasiswaList', 'jenisList', 'tingkatList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'mahasiswa_id'  => 'required|exists:mhs,id',
            'jenis_id'      => 'required|exists:jenis_prestasi,id',
            'tingkat_id'    => 'required|exists:tingkat_prestasi,id',
            'nama_lomba'    => 'required|max:200',
            'penyelenggara' => 'required|max:150',
            'tanggal'       => 'required|date',
            'juara'         => 'nullable|max:50',
            'sertifikat'    => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $data = $request->except(['_token', 'sertifikat']);
        $data['status_verifikasi'] = 'Pending';

        if ($request->hasFile('sertifikat')) {
            $data['sertifikat'] = $request->file('sertifikat')->store('sertifikat', 'public');
        }

        Prestasi::create($data);

        // Kirim notifikasi ke semua admin
        $prestasi = Prestasi::latest()->first();
        $adminUsers = \App\Models\User::where('role', 'admin')->get();
        foreach ($adminUsers as $admin) {
            \App\Models\Notification::create([
                'user_id' => $admin->id,
                'message' => 'Prestasi baru: ' . $request->nama_lomba,
                'url'     => '/prestasi/' . $prestasi->id,
            ]);
        }

        return redirect()->route('prestasi.index')->with('success', 'Data prestasi berhasil ditambahkan!');
    }

    public function show($id)
    {
        $prestasi = Prestasi::with(['mahasiswa', 'jenis', 'tingkat', 'verifikasi.admin'])->findOrFail($id);
        return view('prestasi.show', compact('prestasi'));
    }

    public function edit($id)
    {
        $prestasi      = Prestasi::findOrFail($id);
        $mahasiswaList = Mahasiswa::where('status', 'Aktif')->orderBy('nama')->get();
        $jenisList     = JenisPrestasi::orderBy('nama_jenis')->get();
        $tingkatList   = TingkatPrestasi::orderBy('id')->get();
        return view('prestasi.edit', compact('prestasi', 'mahasiswaList', 'jenisList', 'tingkatList'));
    }

    public function update(Request $request, $id)
    {
        $prestasi = Prestasi::findOrFail($id);

        $request->validate([
            'mahasiswa_id'  => 'required|exists:mhs,id',
            'jenis_id'      => 'required|exists:jenis_prestasi,id',
            'tingkat_id'    => 'required|exists:tingkat_prestasi,id',
            'nama_lomba'    => 'required|max:200',
            'penyelenggara' => 'required|max:150',
            'tanggal'       => 'required|date',
            'juara'         => 'nullable|max:50',
            'sertifikat'    => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $data = $request->except(['_token', '_method', 'sertifikat']);

        if ($request->hasFile('sertifikat')) {
            if ($prestasi->sertifikat) {
                Storage::disk('public')->delete($prestasi->sertifikat);
            }
            $data['sertifikat'] = $request->file('sertifikat')->store('sertifikat', 'public');
        }

        $prestasi->update($data);
        return redirect()->route('prestasi.index')->with('success', 'Data prestasi berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $prestasi = Prestasi::findOrFail($id);
        if ($prestasi->sertifikat) {
            Storage::disk('public')->delete($prestasi->sertifikat);
        }
        $prestasi->delete();
        return redirect()->route('prestasi.index')->with('success', 'Data prestasi berhasil dihapus!');
    }

    public function verifikasi(Request $request, $id)
    {
        $request->validate([
            'status_verifikasi' => 'required|in:Disetujui,Ditolak',
            'catatan'           => 'nullable|string|max:500',
        ]);

        $prestasi = Prestasi::findOrFail($id);
        $prestasi->update(['status_verifikasi' => $request->status_verifikasi]);

        // Kirim notifikasi ke mahasiswa terkait (jika ada user dengan email yang cocok)
        $prestasi->load('mahasiswa');
        $mahasiswaUser = \App\Models\User::where('email', $prestasi->mahasiswa->email)->first();
        if ($mahasiswaUser) {
            \App\Models\Notification::create([
                'user_id' => $mahasiswaUser->id,
                'message' => 'Prestasi "' . $prestasi->nama_lomba . '" telah ' . $request->status_verifikasi,
                'url'     => '/prestasi/' . $prestasi->id,
            ]);
        }

        VerifikasiPrestasi::updateOrCreate(
            ['prestasi_id' => $id],
            [
                'admin_id'           => auth()->id(),
                'tanggal_verifikasi' => today(),
                'catatan'            => $request->catatan,
            ]
        );

        $msg = $request->status_verifikasi === 'Disetujui' ? 'Prestasi berhasil disetujui!' : 'Prestasi ditolak.';
        return redirect()->route('prestasi.show', $id)->with('success', $msg);
    }
}
