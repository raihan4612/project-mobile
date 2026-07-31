<?php

namespace App\Http\Controllers;

use App\Models\JenisPrestasi;
use App\Models\Prestasi;
use Illuminate\Http\Request;

class JenisPrestasiController extends Controller
{
    public function index()
    {
        $dtJenis = JenisPrestasi::withCount('prestasi')->latest()->paginate(10);

        $chartLabels = JenisPrestasi::pluck('nama_jenis');
        $chartData   = JenisPrestasi::withCount('prestasi')->pluck('prestasi_count');

        return view('jenis_prestasi.index', compact('dtJenis', 'chartLabels', 'chartData'));
    }

    public function detail($id)
    {
        $jenis = JenisPrestasi::findOrFail($id);
        $dtPrestasi = Prestasi::with(['mahasiswa', 'jenis', 'tingkat'])
            ->where('jenis_id', $id)
            ->latest()
            ->paginate(10);

        return view('jenis_prestasi.detail', compact('jenis', 'dtPrestasi'));
    }

    public function create()
    {
        return view('jenis_prestasi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_jenis' => 'required|max:50|unique:jenis_prestasi,nama_jenis',
        ]);

        JenisPrestasi::create($request->only('nama_jenis'));
        return redirect()->route('jenis-prestasi.index')->with('success', 'Jenis prestasi berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $jenis = JenisPrestasi::findOrFail($id);
        return view('jenis_prestasi.edit', compact('jenis'));
    }

    public function update(Request $request, $id)
    {
        $jenis = JenisPrestasi::findOrFail($id);

        $request->validate([
            'nama_jenis' => 'required|max:50|unique:jenis_prestasi,nama_jenis,' . $id,
        ]);

        $jenis->update($request->only('nama_jenis'));
        return redirect()->route('jenis-prestasi.index')->with('success', 'Jenis prestasi berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $jenis = JenisPrestasi::findOrFail($id);
        $jenis->delete();
        return redirect()->route('jenis-prestasi.index')->with('success', 'Jenis prestasi berhasil dihapus!');
    }
}
