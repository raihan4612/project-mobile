<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMahasiswaRequest;
use App\Http\Requests\UpdateMahasiswaRequest;
use App\Http\Resources\MahasiswaResource;
use App\Models\Mahasiswa;
use App\Models\User;

class MahasiswaController extends Controller
{
    public function index()
    {
        return MahasiswaResource::collection(Mahasiswa::withCount(['peminjaman', 'prestasi'])->latest()->paginate(20));
    }

    public function store(StoreMahasiswaRequest $request)
    {
        $mahasiswa = Mahasiswa::create($request->validated());

        return new MahasiswaResource($mahasiswa);
    }

    public function show(Mahasiswa $mahasiswa)
    {
        $mahasiswa->loadCount(['peminjaman', 'prestasi']);

        return new MahasiswaResource($mahasiswa);
    }

    public function update(UpdateMahasiswaRequest $request, Mahasiswa $mahasiswa)
    {
        $user = $request->user();

        if ($user->role === 'user' && (int) $user->mahasiswa_id !== (int) $mahasiswa->id) {
            return response()->json(['message' => 'Anda tidak diizinkan mengubah data mahasiswa lain'], 403);
        }

        $mahasiswa->update($request->validated());

        $linkedUser = User::where('mahasiswa_id', $mahasiswa->id)->where('role', 'user')->first();
        if ($linkedUser) {
            $newNama = $request->input('nama', $linkedUser->nama);
            $newEmail = $request->input('email', $linkedUser->email);

            if ($newEmail !== $linkedUser->email
                && User::where('email', $newEmail)->where('id', '!=', $linkedUser->id)->exists()) {
                return response()->json(['message' => 'Email sudah digunakan oleh akun lain'], 422);
            }

            $linkedUser->nama = $newNama;
            $linkedUser->email = $newEmail;
            $linkedUser->save();
        }

        $mahasiswa->loadCount(['peminjaman', 'prestasi']);

        return new MahasiswaResource($mahasiswa);
    }

    public function destroy(Mahasiswa $mahasiswa)
    {
        $mahasiswa->delete();

        return response()->json(['message' => 'Mahasiswa berhasil dihapus']);
    }
}
