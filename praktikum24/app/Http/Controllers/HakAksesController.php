<?php

namespace App\Http\Controllers;

use App\Models\HakAkses;
use Illuminate\Http\Request;

class HakAksesController extends Controller
{
    public function index()
    {
        $roles = HakAkses::all();
        return view('hakakses.index', compact('roles'));
    }

    public function create()
    {
        return view('hakakses.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'nama_role' => 'required|unique:hak_akses,nama_role|max:50',
        'deskripsi' => 'nullable|string',
    ]);

    HakAkses::create([
        'nama_role'  => $request->nama_role,
        'deskripsi'  => $request->deskripsi,
        'can_create' => $request->has('can_create') ? 1 : 0,
        'can_read'   => $request->has('can_read')   ? 1 : 0,
        'can_update' => $request->has('can_update') ? 1 : 0,
        'can_delete' => $request->has('can_delete') ? 1 : 0,
        'can_export' => $request->has('can_export') ? 1 : 0,
        'is_active'  => $request->has('is_active')  ? 1 : 0,
    ]);

    return redirect()->route('hak-akses')->with('success', 'Hak akses berhasil ditambahkan!');
}
    public function edit($id)
    {
        $role = HakAkses::findOrFail($id);
        return view('hakakses.edit', compact('role'));
    }

    public function update(Request $request, $id)
{
    $role = HakAkses::findOrFail($id);

    $request->validate([
        'nama_role' => 'required|max:50|unique:hak_akses,nama_role,' . $id,
        'deskripsi' => 'nullable|string',
    ]);

    $role->update([
        'nama_role'  => $request->nama_role,
        'deskripsi'  => $request->deskripsi,
        'can_create' => $request->has('can_create') ? 1 : 0,
        'can_read'   => $request->has('can_read')   ? 1 : 0,
        'can_update' => $request->has('can_update') ? 1 : 0,
        'can_delete' => $request->has('can_delete') ? 1 : 0,
        'can_export' => $request->has('can_export') ? 1 : 0,
        'is_active'  => $request->has('is_active')  ? 1 : 0,
    ]);

    return redirect()->route('hak-akses')->with('success', 'Hak akses berhasil diperbarui!');
}
    public function destroy($id)
    {
        $role = HakAkses::findOrFail($id);
        $role->delete();
        return redirect()->route('hak-akses')->with('success', 'Hak akses berhasil dihapus!');
    }
}
