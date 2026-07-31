<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\HakAksesController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\PrestasiController;
use App\Http\Controllers\JenisPrestasiController;
use App\Http\Controllers\TingkatPrestasiController;
use App\Http\Controllers\BeasiswaController;
use App\Http\Controllers\ProgramBeasiswaController;
use App\Http\Controllers\DashboardController;

// ─── Auth Routes ──────────────────────────────────────────────────────────────
Route::get('login',   [AuthController::class, 'showLogin'])->name('login');
Route::post('login',  [AuthController::class, 'login'])->name('do-login');
Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ─── ROOT: Dashboard publik ───────────────────────────────────────────────────
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// ─────────────────────────────────────────────────────────────────────────────
// GUEST → index (list) saja, tidak bisa create / show / delete
// ─────────────────────────────────────────────────────────────────────────────
Route::get('data-mahasiswa', [MahasiswaController::class, 'index'])->name('data-mahasiswa');
Route::get('buku',           [BukuController::class,      'index'])->name('buku.index');
Route::get('peminjaman',     [PeminjamanController::class,'index'])->name('peminjaman.index');
Route::get('prestasi',            [PrestasiController::class,       'index'])->name('prestasi.index');
Route::get('jenis-prestasi',          [JenisPrestasiController::class,  'index'])->name('jenis-prestasi.index');
Route::get('jenis-prestasi/{id}/detail', [JenisPrestasiController::class, 'detail'])->name('jenis-prestasi.detail');
Route::get('tingkat-prestasi',        [TingkatPrestasiController::class,'index'])->name('tingkat-prestasi.index');
Route::get('tingkat-prestasi/{id}/detail', [TingkatPrestasiController::class,'detail'])->name('tingkat-prestasi.detail');
Route::get('beasiswa',                [BeasiswaController::class,      'index'])->name('beasiswa.index');
Route::get('program-beasiswa',        [ProgramBeasiswaController::class, 'index'])->name('program-beasiswa.index');

// ─────────────────────────────────────────────────────────────────────────────
// AUTH → semua yang butuh login
// ─────────────────────────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    // ══════════════════════════════════════════════════════════════════════
    // MAHASISWA — create & edit mahasiswa dan prestasi
    // ══════════════════════════════════════════════════════════════════════

    // Mahasiswa - create & edit
    Route::get('create-mahasiswa',      [MahasiswaController::class, 'create'])->name('create-mahasiswa');
    Route::post('simpan-mahasiswa',     [MahasiswaController::class, 'store']) ->name('simpan-mahasiswa');
    Route::get('edit-mahasiswa/{id}',   [MahasiswaController::class, 'edit'])  ->name('edit-mahasiswa');
    Route::put('update-mahasiswa/{id}', [MahasiswaController::class, 'update'])->name('update-mahasiswa');
    Route::get('show-mahasiswa/{id}',   [MahasiswaController::class, 'show'])  ->name('show-mahasiswa');

    // Jenis Prestasi
    Route::get('jenis-prestasi/create',   [JenisPrestasiController::class, 'create'])->name('jenis-prestasi.create');
    Route::post('jenis-prestasi',         [JenisPrestasiController::class, 'store']) ->name('jenis-prestasi.store');
    Route::get('jenis-prestasi/{id}/edit',[JenisPrestasiController::class, 'edit'])  ->name('jenis-prestasi.edit');
    Route::put('jenis-prestasi/{id}',     [JenisPrestasiController::class, 'update'])->name('jenis-prestasi.update');

    // Tingkat Prestasi
    Route::get('tingkat-prestasi/create',   [TingkatPrestasiController::class, 'create'])->name('tingkat-prestasi.create');
    Route::post('tingkat-prestasi',         [TingkatPrestasiController::class, 'store']) ->name('tingkat-prestasi.store');
    Route::get('tingkat-prestasi/{id}/edit',[TingkatPrestasiController::class, 'edit'])  ->name('tingkat-prestasi.edit');
    Route::put('tingkat-prestasi/{id}',     [TingkatPrestasiController::class, 'update'])->name('tingkat-prestasi.update');

    // Program Beasiswa
    Route::get('program-beasiswa/create',   [ProgramBeasiswaController::class, 'create'])->name('program-beasiswa.create');
    Route::post('program-beasiswa',         [ProgramBeasiswaController::class, 'store']) ->name('program-beasiswa.store');
    Route::get('program-beasiswa/{id}/edit',[ProgramBeasiswaController::class, 'edit'])  ->name('program-beasiswa.edit');
    Route::put('program-beasiswa/{id}',     [ProgramBeasiswaController::class, 'update'])->name('program-beasiswa.update');

    // Pengajuan Beasiswa
    Route::get('beasiswa/create',    [BeasiswaController::class, 'create'])->name('beasiswa.create');
    Route::post('beasiswa',          [BeasiswaController::class, 'store']) ->name('beasiswa.store');
    Route::get('beasiswa/{id}/edit', [BeasiswaController::class, 'edit'])  ->name('beasiswa.edit');
    Route::put('beasiswa/{id}',      [BeasiswaController::class, 'update'])->name('beasiswa.update');
    Route::get('beasiswa/{id}',      [BeasiswaController::class, 'show'])  ->name('beasiswa.show');

    // Prestasi - statis dulu, baru dinamis
    Route::get('prestasi/create',    [PrestasiController::class, 'create'])->name('prestasi.create');
    Route::post('prestasi',          [PrestasiController::class, 'store']) ->name('prestasi.store');
    Route::get('prestasi/{id}/edit', [PrestasiController::class, 'edit'])  ->name('prestasi.edit');
    Route::put('prestasi/{id}',      [PrestasiController::class, 'update'])->name('prestasi.update');
    Route::get('prestasi/{id}',      [PrestasiController::class, 'show'])  ->name('prestasi.show');

    // ══════════════════════════════════════════════════════════════════════
    // PETUGAS — full CRUD buku & peminjaman
    // ══════════════════════════════════════════════════════════════════════

    // Buku - statis dulu, baru dinamis
    Route::get('buku/create',    [BukuController::class, 'create'])->name('buku.create');
    Route::post('buku',          [BukuController::class, 'store']) ->name('buku.store');
    Route::get('buku/{id}/edit', [BukuController::class, 'edit'])  ->name('buku.edit');
    Route::put('buku/{id}',      [BukuController::class, 'update'])->name('buku.update');
    Route::delete('buku/{id}',   [BukuController::class, 'destroy'])->name('buku.destroy');
    Route::get('buku/{id}',      [BukuController::class, 'show'])  ->name('buku.show');

    // Peminjaman - statis dulu, baru dinamis
    Route::get('peminjaman/create',           [PeminjamanController::class, 'create'])      ->name('peminjaman.create');
    Route::post('peminjaman',                 [PeminjamanController::class, 'store'])       ->name('peminjaman.store');
    Route::post('peminjaman/{id}/kembalikan', [PeminjamanController::class, 'pengembalian'])->name('peminjaman.pengembalian');
    Route::delete('peminjaman/{id}',          [PeminjamanController::class, 'destroy'])     ->name('peminjaman.destroy');
    Route::get('peminjaman/{id}',             [PeminjamanController::class, 'show'])        ->name('peminjaman.show');

    Route::get('petugas-list',          [PetugasController::class, 'index'])  ->name('petugas.index');
    Route::get('petugas-create',        [PetugasController::class, 'create']) ->name('petugas.create');

    // ══════════════════════════════════════════════════════════════════════
    // PROFILE
    // ══════════════════════════════════════════════════════════════════════
    Route::get('profile',       [\App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
    Route::put('profile',       [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/password', [\App\Http\Controllers\ProfileController::class, 'password'])->name('profile.password');

    Route::post('notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'readAll'])->name('notifications.read-all');
    Route::post('notifications/read/{notification}', [\App\Http\Controllers\NotificationController::class, 'read'])->name('notifications.read');

    // ══════════════════════════════════════════════════════════════════════
    // ADMIN ONLY — delete, verifikasi, hak akses, petugas
    // ══════════════════════════════════════════════════════════════════════
    Route::middleware('admin')->group(function () {

        // Mahasiswa - delete
        Route::delete('hapus-mahasiswa/{id}', [MahasiswaController::class, 'destroy'])->name('hapus-mahasiswa');

        // Prestasi - delete & verifikasi
        Route::delete('prestasi/{id}',          [PrestasiController::class, 'destroy'])   ->name('prestasi.destroy');
        Route::post('prestasi/{id}/verifikasi', [PrestasiController::class, 'verifikasi'])->name('prestasi.verifikasi');

        // Jenis Prestasi - delete
        Route::delete('jenis-prestasi/{id}', [JenisPrestasiController::class, 'destroy'])->name('jenis-prestasi.destroy');

        // Tingkat Prestasi - delete
        Route::delete('tingkat-prestasi/{id}', [TingkatPrestasiController::class, 'destroy'])->name('tingkat-prestasi.destroy');

        // Program Beasiswa - delete
        Route::delete('program-beasiswa/{id}', [ProgramBeasiswaController::class, 'destroy'])->name('program-beasiswa.destroy');

        // Pengajuan Beasiswa - delete
        Route::delete('beasiswa/{id}', [BeasiswaController::class, 'destroy'])->name('beasiswa.destroy');

        // SPK Fuzzy — Hitung Rekomendasi
        Route::post('beasiswa/hitung-rekomendasi', [BeasiswaController::class, 'hitungRekomendasi'])->name('beasiswa.hitung-rekomendasi');

        // Hak Akses - full CRUD
        Route::get('hak-akses',               [HakAksesController::class, 'index'])  ->name('hak-akses');
        Route::get('create-hak-akses',        [HakAksesController::class, 'create']) ->name('create-hak-akses');
        Route::post('simpan-hak-akses',       [HakAksesController::class, 'store'])  ->name('simpan-hak-akses');
        Route::get('edit-hak-akses/{id}',     [HakAksesController::class, 'edit'])   ->name('edit-hak-akses');
        Route::put('update-hak-akses/{id}',   [HakAksesController::class, 'update']) ->name('update-hak-akses');
        Route::delete('hapus-hak-akses/{id}', [HakAksesController::class, 'destroy'])->name('hapus-hak-akses');

        // Petugas - full CRUD

        Route::post('petugas-simpan',       [PetugasController::class, 'store'])  ->name('petugas.store');
        Route::get('petugas-detail/{id}',   [PetugasController::class, 'show'])   ->name('petugas.show');
        Route::get('petugas-edit/{id}',     [PetugasController::class, 'edit'])   ->name('petugas.edit');
        Route::put('petugas-update/{id}',   [PetugasController::class, 'update']) ->name('petugas.update');
        Route::delete('petugas-hapus/{id}', [PetugasController::class, 'destroy'])->name('petugas.destroy');
    });
});