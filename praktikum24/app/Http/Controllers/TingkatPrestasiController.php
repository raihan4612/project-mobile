<?php

namespace App\Http\Controllers;

use App\Models\TingkatPrestasi;
use App\Models\Prestasi;
use Illuminate\Http\Request;

class TingkatPrestasiController extends Controller
{
    public function index()
    {
        $dtTingkat = TingkatPrestasi::withCount('prestasi')->latest()->paginate(10);

        $chartLabels = TingkatPrestasi::pluck('nama_tingkat');
        $chartData   = TingkatPrestasi::withCount('prestasi')->pluck('prestasi_count');

        return view('tingkat_prestasi.index', compact('dtTingkat', 'chartLabels', 'chartData'));
    }

    public function detail($id)
    {
        $tingkat = TingkatPrestasi::findOrFail($id);
        $dtPrestasi = Prestasi::with(['mahasiswa', 'jenis', 'tingkat'])
            ->where('tingkat_id', $id)
            ->latest()
            ->paginate(10);

        return view('tingkat_prestasi.detail', compact('tingkat', 'dtPrestasi'));
    }

    public function create()
    {
        return view('tingkat_prestasi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_tingkat' => 'required|max:50|unique:tingkat_prestasi,nama_tingkat',
        ]);

        TingkatPrestasi::create($request->only('nama_tingkat'));
        return redirect()->route('tingkat-prestasi.index')->with('success', 'Tingkat prestasi berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $tingkat = TingkatPrestasi::findOrFail($id);
        return view('tingkat_prestasi.edit', compact('tingkat'));
    }

    public function update(Request $request, $id)
    {
        $tingkat = TingkatPrestasi::findOrFail($id);

        $request->validate([
            'nama_tingkat' => 'required|max:50|unique:tingkat_prestasi,nama_tingkat,' . $id,
        ]);

        $tingkat->update($request->only('nama_tingkat'));
        return redirect()->route('tingkat-prestasi.index')->with('success', 'Tingkat prestasi berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $tingkat = TingkatPrestasi::findOrFail($id);
        $tingkat->delete();
        return redirect()->route('tingkat-prestasi.index')->with('success', 'Tingkat prestasi berhasil dihapus!');
    }
}
