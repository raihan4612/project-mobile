<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        // Kalau sudah login, langsung redirect sesuai role
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();

            // Redirect berbeda sesuai role
            return $this->redirectByRole(Auth::user());
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('dashboard');
    }

    // ── Helper: tentukan redirect berdasarkan role ────────────────────────
    private function redirectByRole($user)
    {
        return match($user->role) {
            'admin'      => redirect()->route('data-mahasiswa') // Admin → Data Mahasiswa
                                ->with('success', 'Selamat datang, Admin ' . $user->nama . '!'),
            'petugas'    => redirect()->route('peminjaman.index') // Petugas → Peminjaman
                                ->with('success', 'Selamat datang, ' . $user->nama . '!'),
            'mahasiswa'  => redirect()->route('prestasi.index')  // Mahasiswa → Prestasi
                                ->with('success', 'Selamat datang, ' . $user->nama . '!'),
            default      => redirect()->route('data-mahasiswa')  // Guest → Mahasiswa (read only)
                                ->with('success', 'Selamat datang!'),
        };
    }
}
