<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api;

Route::post('/logout', function (Request $request) {
    $request->user()->currentAccessToken()->delete();

    return response()->json(['message' => 'Logout berhasil']);
})->middleware('auth:sanctum');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'nim'      => 'required|string',
        'password' => 'required',
    ]);

    $user = null;

    if (Auth::attempt(['nim' => $credentials['nim'], 'password' => $credentials['password']])) {
        $user = Auth::user();
    } elseif (str_contains($credentials['nim'], '@')
        && Auth::attempt(['email' => $credentials['nim'], 'password' => $credentials['password']])) {
        $user = Auth::user();
    }

    if ($user) {
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'user'  => [
                'id'           => $user->id,
                'nama'         => $user->nama,
                'email'        => $user->email,
                'role'         => $user->role,
                'nim'          => $user->nim,
                'mahasiswa_id' => $user->mahasiswa_id,
            ],
            'token' => $token,
        ]);
    }

    return response()->json(['message' => 'NIM/email atau password salah'], 401);
});

Route::middleware('auth:sanctum')->name('api.')->group(function () {

    Route::apiResource('mahasiswa', Api\MahasiswaController::class);
    Route::apiResource('buku', Api\BukuController::class);

    Route::apiResource('peminjaman', Api\PeminjamanController::class);
    Route::post('peminjaman/{peminjaman}/pengembalian', [Api\PeminjamanController::class, 'pengembalian']);

    Route::apiResource('petugas', Api\PetugasController::class);

    Route::apiResource('prestasi', Api\PrestasiController::class);
    Route::post('prestasi/{prestasi}/verifikasi', [Api\PrestasiController::class, 'verifikasi'])->name('prestasi.verifikasi');

    Route::apiResource('hak-akses', Api\HakAksesController::class);
    Route::apiResource('jenis-prestasi', Api\JenisPrestasiController::class);
    Route::apiResource('tingkat-prestasi', Api\TingkatPrestasiController::class);

    Route::get('dashboard', [Api\DashboardController::class, 'index'])->name('dashboard');

    Route::apiResource('beasiswa', Api\BeasiswaController::class);
    Route::post('beasiswa/hitung-rekomendasi', [Api\BeasiswaController::class, 'hitungRekomendasi'])->name('beasiswa.hitung-rekomendasi');
    Route::apiResource('program-beasiswa', Api\ProgramBeasiswaController::class);

});
