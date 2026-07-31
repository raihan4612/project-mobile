@extends('layouts.app')

@section('title', 'Detail Buku')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0 fw-bold"><i class="bi bi-book-fill text-primary me-2"></i>Detail Buku</h4>
    <a href="{{ route('buku.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="row">
    <div class="col-md-5">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white fw-semibold">
                <i class="bi bi-info-circle me-2"></i>Informasi Buku
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr><th width="40%">Kode Buku</th><td>: <span class="fw-bold text-primary">{{ $buku->kode_buku }}</span></td></tr>
                    <tr><th>Judul</th><td>: {{ $buku->judul }}</td></tr>
                    <tr><th>Pengarang</th><td>: {{ $buku->pengarang }}</td></tr>
                    <tr><th>Penerbit</th><td>: {{ $buku->penerbit }}</td></tr>
                    <tr><th>Tahun Terbit</th><td>: {{ $buku->tahun_terbit }}</td></tr>
                    <tr><th>Kategori</th><td>: <span class="badge bg-secondary">{{ $buku->kategori }}</span></td></tr>
                    <tr><th>Jumlah Stok</th><td>: {{ $buku->jumlah_stok }}</td></tr>
                    <tr><th>Tersedia</th><td>: <strong class="{{ $buku->jumlah_tersedia > 0 ? 'text-success' : 'text-danger' }}">{{ $buku->jumlah_tersedia }}</strong></td></tr>
                    <tr><th>Status</th><td>:
                        @if($buku->status === 'Tersedia')
                            <span class="badge bg-success">Tersedia</span>
                        @else
                            <span class="badge bg-danger">Habis</span>
                        @endif
                    </td></tr>
                    <tr><th>Deskripsi</th><td>: {{ $buku->deskripsi ?? '-' }}</td></tr>
                </table>
            </div>
            {{-- Aksi edit & hapus: petugas & admin --}}
            @if(auth()->user()->isAdmin() || auth()->user()->role === 'petugas')
            <div class="card-footer d-flex gap-2">
                <a href="{{ route('buku.edit', $buku->id) }}" class="btn btn-warning btn-sm">
                    <i class="bi bi-pencil me-1"></i> Edit
                </a>
                <form action="{{ route('buku.destroy', $buku->id) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('Yakin hapus buku ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="bi bi-trash me-1"></i> Hapus
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>

    <div class="col-md-7">
        <div class="card">
            <div class="card-header bg-info text-white fw-semibold">
                <i class="bi bi-clock-history me-2"></i>Riwayat Peminjaman
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Mahasiswa</th>
                                <th>Tgl Pinjam</th>
                                <th>Tgl Kembali</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($peminjaman as $p)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $p->mahasiswa->nama }}</div>
                                    <small class="text-muted">{{ $p->mahasiswa->nim }}</small>
                                </td>
                                <td>{{ $p->tanggal_pinjam->format('d/m/Y') }}</td>
                                <td>{{ $p->tanggal_kembali_rencana->format('d/m/Y') }}</td>
                                <td>
                                    @php
                                        $badge = match($p->status) {
                                            'Dipinjam' => 'bg-warning text-dark',
                                            'Dikembalikan' => 'bg-success',
                                            'Terlambat' => 'bg-danger',
                                            default => 'bg-secondary'
                                        };
                                    @endphp
                                    <span class="badge {{ $badge }}">{{ $p->status }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-3">
                                    Belum ada riwayat peminjaman
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
