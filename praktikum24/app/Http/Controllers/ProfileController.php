<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $mahasiswa = null;
        if ($user->isMahasiswa()) {
            $mahasiswa = Mahasiswa::where('email', $user->email)->first();
        }
        return view('profile.index', compact('user', 'mahasiswa'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'nama'  => 'required|max:100',
            'email' => 'required|email|max:100|unique:users,email,' . $user->id,
        ]);

        $user->update($request->only('nama', 'email'));

        if ($user->isMahasiswa()) {
            $mahasiswa = Mahasiswa::where('email', $user->email)->first();
            if ($mahasiswa) {
                $mahasiswa->update($request->only('no_hp', 'alamat'));
            }
        }

        return redirect()->route('profile.index')->with('success', 'Profil berhasil diperbarui!');
    }

    public function password(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:6|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Password saat ini tidak sesuai!');
        }

        $user->update(['password' => Hash::make($request->password)]);

        return redirect()->route('profile.index')->with('success', 'Password berhasil diubah!');
    }
}
