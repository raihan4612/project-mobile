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
            'login'    => 'required|string',
            'password' => 'required',
        ]);

        $login    = $request->input('login');
        $password = $request->input('password');

        $user = null;

        if (Auth::attempt(['nim' => $login, 'password' => $password])) {
            $user = Auth::user();
        } elseif (str_contains($login, '@')
            && Auth::attempt(['email' => $login, 'password' => $password])) {
            $user = Auth::user();
        }

        if ($user) {
            $request->session()->regenerate();

            // Redirect berbeda sesuai role
            return $this->redirectByRole($user);
        }

        return back()->withErrors([
            'login' => 'NIM/email atau password salah.',
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
