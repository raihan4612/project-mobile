<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $dtMhs = Mahasiswa::when($search, function ($q, $s) {
            return $q->where(function ($q) use ($s) {
                $q->where('nama', 'like', "%{$s}%")
                  ->orWhere('nim', 'like', "%{$s}%")
                  ->orWhere('prodi', 'like', "%{$s}%")
                  ->orWhere('fakultas', 'like', "%{$s}%")
                  ->orWhere('no_hp', 'like', "%{$s}%");
            });
        })->when($status, function ($q, $s) {
            return $q->where('status', $s);
        })->latest()->paginate(10)->withQueryString();

        return view('mahasiswa.index', compact('dtMhs'));
    }

    public function create()
    {
        return view('mahasiswa.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nim'           => 'required|unique:mhs,nim|max:20',
            'nama'          => 'required|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date',
            'tempat_lahir'  => 'required|max:100',
            'alamat'        => 'required',
            'kota'          => 'required|max:100',
            'provinsi'      => 'required|max:100',
            'kode_pos'      => 'nullable|max:10',
            'no_hp'         => 'required|max:20',
            'email'         => 'required|email|unique:mhs,email|max:100',
            'prodi'         => 'required|max:100',
            'fakultas'      => 'required|max:100',
            'semester'      => 'required|integer|min:1|max:14',
            'ipk'           => 'nullable|numeric|min:0|max:4',
            'tahun_masuk'   => 'required|digits:4',
            'status'        => 'required|in:Aktif,Cuti,Lulus,Dropout',
        ]);

        Mahasiswa::create($request->except('_token'));
        return redirect()->route('data-mahasiswa')->with('success', 'Data mahasiswa berhasil ditambahkan!');
    }

    public function show($id)
    {
        $dtMhs = Mahasiswa::findOrFail($id);
        return view('mahasiswa.show', compact('dtMhs'));
    }

    public function edit($id)
    {
        $dtMhs = Mahasiswa::findOrFail($id);
        return view('mahasiswa.edit', compact('dtMhs'));
    }

    public function update(Request $request, $id)
    {
        $dtMhs = Mahasiswa::findOrFail($id);

        $request->validate([
            'nim'           => 'required|max:20|unique:mhs,nim,' . $id,
            'nama'          => 'required|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date',
            'tempat_lahir'  => 'required|max:100',
            'alamat'        => 'required',
            'kota'          => 'required|max:100',
            'provinsi'      => 'required|max:100',
            'kode_pos'      => 'nullable|max:10',
            'no_hp'         => 'required|max:20',
            'email'         => 'required|email|max:100|unique:mhs,email,' . $id,
            'prodi'         => 'required|max:100',
            'fakultas'      => 'required|max:100',
            'semester'      => 'required|integer|min:1|max:14',
            'ipk'           => 'nullable|numeric|min:0|max:4',
            'tahun_masuk'   => 'required|digits:4',
            'status'        => 'required|in:Aktif,Cuti,Lulus,Dropout',
        ]);

        $dtMhs->update($request->except('_token', '_method'));
        return redirect()->route('data-mahasiswa')->with('success', 'Data mahasiswa berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $dtMhs = Mahasiswa::findOrFail($id);
        $dtMhs->delete();
        return redirect()->route('data-mahasiswa')->with('success', 'Data mahasiswa berhasil dihapus!');
    }
}
