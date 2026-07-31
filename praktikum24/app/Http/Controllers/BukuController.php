<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        $search   = $request->input('search');
        $kategori = $request->input('kategori');
        $status   = $request->input('status');

        $dtBuku = Buku::when($search, function ($q, $s) {
            return $q->where(function ($q) use ($s) {
                $q->where('judul', 'like', "%{$s}%")
                  ->orWhere('pengarang', 'like', "%{$s}%")
                  ->orWhere('kode_buku', 'like', "%{$s}%")
                  ->orWhere('penerbit', 'like', "%{$s}%");
            });
        })->when($kategori, function ($q, $k) {
            return $q->where('kategori', $k);
        })->when($status, function ($q, $s) {
            return $q->where('status', $s);
        })->latest()->paginate(10)->withQueryString();

        return view('buku.index', compact('dtBuku'));
    }

    public function create()
    {
        return view('buku.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_buku'    => 'required|unique:buku,kode_buku|max:20',
            'judul'        => 'required|max:200',
            'pengarang'    => 'required|max:100',
            'penerbit'     => 'required|max:100',
            'tahun_terbit' => 'required|digits:4',
            'kategori'     => 'required|max:50',
            'jumlah_stok'  => 'required|integer|min:1',
            'deskripsi'    => 'nullable',
        ]);

        $data = $request->except('_token');
        $data['jumlah_tersedia'] = $data['jumlah_stok'];
        $data['status'] = 'Tersedia';

        Buku::create($data);
        return redirect()->route('buku.index')->with('success', 'Data buku berhasil ditambahkan!');
    }

    public function show($id)
    {
        $buku = Buku::findOrFail($id);
        $peminjaman = $buku->peminjaman()->with('mahasiswa')->latest()->get();
        return view('buku.show', compact('buku', 'peminjaman'));
    }

    public function edit($id)
    {
        $buku = Buku::findOrFail($id);
        return view('buku.edit', compact('buku'));
    }

    public function update(Request $request, $id)
    {
        $buku = Buku::findOrFail($id);

        $request->validate([
            'kode_buku'    => 'required|max:20|unique:buku,kode_buku,' . $id,
            'judul'        => 'required|max:200',
            'pengarang'    => 'required|max:100',
            'penerbit'     => 'required|max:100',
            'tahun_terbit' => 'required|digits:4',
            'kategori'     => 'required|max:50',
            'jumlah_stok'  => 'required|integer|min:1',
            'deskripsi'    => 'nullable',
        ]);

        $data = $request->except('_token', '_method');

        // Hitung ulang jumlah_tersedia ketika stok diubah
        $selisih = $data['jumlah_stok'] - $buku->jumlah_stok;
        $data['jumlah_tersedia'] = max(0, $buku->jumlah_tersedia + $selisih);
        $data['status'] = $data['jumlah_tersedia'] > 0 ? 'Tersedia' : 'Habis';

        $buku->update($data);
        return redirect()->route('buku.index')->with('success', 'Data buku berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $buku = Buku::findOrFail($id);

        // Cek apakah ada peminjaman aktif
        if ($buku->peminjaman()->where('status', 'Dipinjam')->exists()) {
            return redirect()->route('buku.index')->with('error', 'Buku tidak dapat dihapus karena masih dalam peminjaman aktif!');
        }

        $buku->delete();
        return redirect()->route('buku.index')->with('success', 'Data buku berhasil dihapus!');
    }
}
