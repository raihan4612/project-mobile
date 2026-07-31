<?php

namespace App\Http\Controllers;

use App\Models\Petugas;
use Illuminate\Http\Request;

class PetugasController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $dtPetugas = Petugas::when($search, function ($q, $s) {
            return $q->where(function ($q) use ($s) {
                $q->where('nama', 'like', "%{$s}%")
                  ->orWhere('kode_petugas', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhere('jabatan', 'like', "%{$s}%")
                  ->orWhere('no_hp', 'like', "%{$s}%");
            });
        })->when($status, function ($q, $s) {
            return $q->where('status', $s);
        })->latest()->paginate(10)->withQueryString();

        return view('petugas.index', compact('dtPetugas'));
    }

    public function create()
    {
        return view('petugas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'    => 'required|string|max:100',
            'email'   => 'required|email|unique:petugas,email',
            'no_hp'   => 'nullable|string|max:20',
            'jabatan' => 'required|string|max:50',
            'status'  => 'required|in:Aktif,Nonaktif',
        ]);

        // Auto-generate kode petugas
        $kode = 'PTG-' . str_pad(Petugas::count() + 1, 3, '0', STR_PAD_LEFT);

        Petugas::create([
            'kode_petugas' => $kode,
            'nama'         => $request->nama,
            'email'        => $request->email,
            'no_hp'        => $request->no_hp,
            'jabatan'      => $request->jabatan,
            'status'       => $request->status,
        ]);

        return redirect()->route('petugas.index')->with('success', 'Petugas berhasil ditambahkan!');
    }

    public function show($id)
    {
        $petugas = Petugas::withCount('peminjaman')->findOrFail($id);
        return view('petugas.show', compact('petugas'));
    }

    public function edit($id)
    {
        $petugas = Petugas::findOrFail($id);
        return view('petugas.edit', compact('petugas'));
    }

    public function update(Request $request, $id)
    {
        $petugas = Petugas::findOrFail($id);

        $request->validate([
            'nama'    => 'required|string|max:100',
            'email'   => 'required|email|unique:petugas,email,' . $id,
            'no_hp'   => 'nullable|string|max:20',
            'jabatan' => 'required|string|max:50',
            'status'  => 'required|in:Aktif,Nonaktif',
        ]);

        $petugas->update($request->only(['nama', 'email', 'no_hp', 'jabatan', 'status']));

        return redirect()->route('petugas.index')->with('success', 'Data petugas berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $petugas = Petugas::findOrFail($id);

        // Cegah hapus jika masih punya peminjaman aktif
        if ($petugas->peminjaman()->where('status', 'Dipinjam')->exists()) {
            return redirect()->route('petugas.index')
                ->with('error', 'Tidak dapat menghapus petugas yang masih memiliki peminjaman aktif!');
        }

        $petugas->delete();
        return redirect()->route('petugas.index')->with('success', 'Data petugas berhasil dihapus!');
    }
}