<?php

namespace App\Http\Controllers;

use App\Models\ProgramBeasiswa;
use Illuminate\Http\Request;

class ProgramBeasiswaController extends Controller
{
    public function index()
    {
        $dtProgram = ProgramBeasiswa::withCount('beasiswa')->latest()->paginate(10);
        return view('program_beasiswa.index', compact('dtProgram'));
    }

    public function create()
    {
        return view('program_beasiswa.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_beasiswa'  => 'required|max:200',
            'penyelenggara'  => 'required|max:150',
            'tahun_akademik' => 'required|max:20',
            'jumlah_dana'    => 'required|numeric|min:0',
        ]);

        ProgramBeasiswa::create($request->only(['nama_beasiswa', 'penyelenggara', 'tahun_akademik', 'jumlah_dana']));
        return redirect()->route('program-beasiswa.index')->with('success', 'Program beasiswa berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $program = ProgramBeasiswa::findOrFail($id);
        return view('program_beasiswa.edit', compact('program'));
    }

    public function update(Request $request, $id)
    {
        $program = ProgramBeasiswa::findOrFail($id);

        $request->validate([
            'nama_beasiswa'  => 'required|max:200',
            'penyelenggara'  => 'required|max:150',
            'tahun_akademik' => 'required|max:20',
            'jumlah_dana'    => 'required|numeric|min:0',
        ]);

        $program->update($request->only(['nama_beasiswa', 'penyelenggara', 'tahun_akademik', 'jumlah_dana']));
        return redirect()->route('program-beasiswa.index')->with('success', 'Program beasiswa berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $program = ProgramBeasiswa::findOrFail($id);
        $program->delete();
        return redirect()->route('program-beasiswa.index')->with('success', 'Program beasiswa berhasil dihapus!');
    }
}
