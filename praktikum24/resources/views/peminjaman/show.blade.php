@extends('layouts.app')

@section('title', 'Detail Peminjaman')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0 fw-bold"><i class="bi bi-journal-bookmark-fill text-primary me-2"></i>Detail Peminjaman</h4>
    <a href="{{ route('peminjaman.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

@php
    $badge = match($peminjaman->status) {
        'Dipinjam'     => 'bg-warning text-dark',
        'Dikembalikan' => 'bg-success',
        'Terlambat'    => 'bg-danger',
        default        => 'bg-secondary'
    };
@endphp

<div class="row g-4">
    {{-- Kolom Kiri: Info Transaksi --}}
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header bg-primary text-white fw-semibold">
                <i class="bi bi-receipt me-2"></i>Info Transaksi
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr><th width="45%">Kode Peminjaman</th><td>: <strong class="text-primary">{{ $peminjaman->kode_peminjaman }}</strong></td></tr>
                    <tr><th>Status</th><td>: <span class="badge {{ $badge }}">{{ $peminjaman->status }}</span></td></tr>
                    <tr><th>Tanggal Pinjam</th><td>: {{ $peminjaman->tanggal_pinjam->format('d F Y') }}</td></tr>
                    <tr><th>Rencana Kembali</th><td>: {{ $peminjaman->tanggal_kembali_rencana->format('d F Y') }}</td></tr>
                    <tr>
                        <th>Aktual Kembali</th>
                        <td>: {{ $peminjaman->tanggal_kembali_aktual ? $peminjaman->tanggal_kembali_aktual->format('d F Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <th>Denda</th>
                        <td>:
                            @if($peminjaman->denda > 0)
                                <span class="text-danger fw-bold">Rp {{ number_format($peminjaman->denda,0,',','.') }}</span>
                            @else
                                <span class="text-muted">Tidak ada denda</span>
                            @endif
                        </td>
                    </tr>
                    <tr><th>Catatan</th><td>: {{ $peminjaman->catatan ?? '-' }}</td></tr>
                </table>
            </div>
        </div>
    </div>

    {{-- Kolom Kanan: Mahasiswa, Buku, Petugas --}}
    <div class="col-md-6 d-flex flex-column gap-3">

        {{-- Data Mahasiswa --}}
        <div class="card">
            <div class="card-header bg-success text-white fw-semibold">
                <i class="bi bi-person-fill me-2"></i>Data Mahasiswa
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr><th width="40%">NIM</th><td>: {{ $peminjaman->mahasiswa->nim }}</td></tr>
                    <tr><th>Nama</th><td>: {{ $peminjaman->mahasiswa->nama }}</td></tr>
                    <tr><th>Prodi</th><td>: {{ $peminjaman->mahasiswa->prodi }}</td></tr>
                    <tr><th>No. HP</th><td>: {{ $peminjaman->mahasiswa->no_hp }}</td></tr>
                </table>
            </div>
        </div>

        {{-- Data Buku --}}
        <div class="card">
            <div class="card-header bg-info text-white fw-semibold">
                <i class="bi bi-book-fill me-2"></i>Data Buku
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr><th width="40%">Kode Buku</th><td>: {{ $peminjaman->buku->kode_buku }}</td></tr>
                    <tr><th>Judul</th><td>: {{ $peminjaman->buku->judul }}</td></tr>
                    <tr><th>Pengarang</th><td>: {{ $peminjaman->buku->pengarang }}</td></tr>
                    <tr><th>Kategori</th><td>: <span class="badge bg-secondary">{{ $peminjaman->buku->kategori }}</span></td></tr>
                </table>
            </div>
        </div>

        {{-- Data Petugas --}}
        <div class="card">
            <div class="card-header bg-secondary text-white fw-semibold">
                <i class="bi bi-person-badge-fill me-2"></i>Petugas yang Melayani
            </div>
            <div class="card-body">
                @if($peminjaman->petugas)
                    <table class="table table-borderless mb-0">
                        <tr><th width="40%">Kode</th><td>: {{ $peminjaman->petugas->kode_petugas }}</td></tr>
                        <tr><th>Nama</th><td>: {{ $peminjaman->petugas->nama }}</td></tr>
                        <tr><th>Jabatan</th><td>: {{ $peminjaman->petugas->jabatan }}</td></tr>
                        <tr><th>No. HP</th><td>: {{ $peminjaman->petugas->no_hp ?? '-' }}</td></tr>
                    </table>
                @else
                    <p class="text-muted mb-0">Data petugas tidak tersedia.</p>
                @endif
            </div>
        </div>

    </div>
</div>

{{-- Tombol pengembalian: petugas & admin --}}
@if((auth()->user()->isAdmin() || auth()->user()->role === 'petugas') && $peminjaman->status === 'Dipinjam')
<div class="mt-3">
    <form action="{{ route('peminjaman.pengembalian', $peminjaman->id) }}"
          method="POST"
          onsubmit="return confirm('Konfirmasi pengembalian buku ini?')">
        @csrf
        <button type="submit" class="btn btn-success">
            <i class="bi bi-arrow-return-left me-1"></i> Proses Pengembalian
        </button>
    </form>
</div>
@endif
@endsection
