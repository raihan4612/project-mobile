@extends('layouts.app')

@section('title', 'Peminjaman Buku')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-0 fw-bold"><i class="bi bi-journal-bookmark-fill text-primary me-2"></i>Peminjaman Buku</h4>
        <small class="text-muted">Total: {{ $dtPeminjaman->total() }} transaksi</small>
    </div>
    @auth
        @if(auth()->user()->isAdmin() || auth()->user()->role === 'petugas')
        <a href="{{ route('peminjaman.create') }}" class="btn btn-primary mt-2 mt-md-0">
            <i class="bi bi-plus-circle me-1"></i> Catat Peminjaman
        </a>
        @endif
    @else
        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm mt-2 mt-md-0">
            <i class="bi bi-box-arrow-in-right me-1"></i> Login untuk kelola data
        </a>
    @endauth
</div>

{{-- Search & Filter --}}
<form method="GET" action="{{ route('peminjaman.index') }}" class="row g-2 mb-3">
    <div class="col-md-5">
        <div class="input-group input-group-sm">
            <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
            <input type="text" name="search" class="form-control" placeholder="Cari kode peminjaman, nama mahasiswa..." value="{{ request('search') }}">
        </div>
    </div>
    <div class="col-md-3">
        <select name="status" class="form-select form-select-sm">
            <option value="">Semua Status</option>
            <option value="Dipinjam" {{ request('status') == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
            <option value="Dikembalikan" {{ request('status') == 'Dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
            <option value="Terlambat" {{ request('status') == 'Terlambat' ? 'selected' : '' }}>Terlambat</option>
        </select>
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-sm btn-primary w-100"><i class="bi bi-filter"></i> Filter</button>
    </div>
    <div class="col-md-2">
        <a href="{{ route('peminjaman.index') }}" class="btn btn-sm btn-outline-secondary w-100"><i class="bi bi-arrow-counterclockwise"></i> Reset</a>
    </div>
</form>

{{-- Ringkasan Status --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0" style="background: linear-gradient(135deg,#fff3cd,#ffe08a);">
            <div class="card-body d-flex align-items-center gap-3">
                <i class="bi bi-hourglass-split fs-2 text-warning"></i>
                <div>
                    <div class="fs-4 fw-bold">{{ $totalDipinjam }}</div>
                    <small class="text-muted">Sedang Dipinjam</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0" style="background: linear-gradient(135deg,#d1fae5,#a7f3d0);">
            <div class="card-body d-flex align-items-center gap-3">
                <i class="bi bi-check-circle-fill fs-2 text-success"></i>
                <div>
                    <div class="fs-4 fw-bold">{{ $totalDikembalikan }}</div>
                    <small class="text-muted">Sudah Dikembalikan</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0" style="background: linear-gradient(135deg,#fee2e2,#fca5a5);">
            <div class="card-body d-flex align-items-center gap-3">
                <i class="bi bi-exclamation-triangle-fill fs-2 text-danger"></i>
                <div>
                    <div class="fs-4 fw-bold">{{ $totalTerlambat }}</div>
                    <small class="text-muted">Terlambat</small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width:4%">#</th>
                        <th>Kode</th>
                        <th>Mahasiswa</th>
                        <th>Buku</th>
                        <th>Tgl Pinjam</th>
                        <th>Tgl Kembali</th>
                        <th>Status</th>
                        <th>Denda</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($dtPeminjaman as $item)
                    <tr>
                        <td class="text-muted">{{ $dtPeminjaman->firstItem() + $loop->index }}</td>
                        <td><span class="fw-semibold text-primary" style="font-size:0.8rem">{{ $item->kode_peminjaman }}</span></td>
                        <td>
                            <div class="fw-semibold">{{ $item->mahasiswa->nama }}</div>
                            <small class="text-muted">{{ $item->mahasiswa->nim }}</small>
                        </td>
                        <td>
                            <div>{{ Str::limit($item->buku->judul, 30) }}</div>
                            <small class="text-muted">{{ $item->buku->kode_buku }}</small>
                        </td>
                        <td>{{ $item->tanggal_pinjam->format('d/m/Y') }}</td>
                        <td>
                            {{ $item->tanggal_kembali_rencana->format('d/m/Y') }}
                            @if($item->status === 'Dipinjam' && $item->tanggal_kembali_rencana->isPast())
                                <br><small class="text-danger fw-bold"><i class="bi bi-exclamation-circle"></i> Lewat batas!</small>
                            @endif
                        </td>
                        <td>
                            @php
                                $badge = match($item->status) {
                                    'Dipinjam'     => 'bg-warning text-dark',
                                    'Dikembalikan' => 'bg-success',
                                    'Terlambat'    => 'bg-danger',
                                    default        => 'bg-secondary',
                                };
                            @endphp
                            <span class="badge {{ $badge }}">{{ $item->status }}</span>
                        </td>
                        <td>
                            @if($item->denda > 0)
                                <span class="text-danger fw-semibold">Rp {{ number_format($item->denda,0,',','.') }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @auth
                            <a href="{{ route('peminjaman.show', $item->id) }}"
                               class="btn btn-sm btn-outline-info" title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>

                            @if(auth()->user()->isAdmin() || auth()->user()->role === 'petugas')
                                @if($item->status === 'Dipinjam')
                                <form action="{{ route('peminjaman.pengembalian', $item->id) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Konfirmasi pengembalian buku ini?')">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-success" title="Kembalikan">
                                        <i class="bi bi-arrow-return-left"></i>
                                    </button>
                                </form>
                                @endif
                                @if($item->status !== 'Dipinjam')
                                <form action="{{ route('peminjaman.destroy', $item->id) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Yakin hapus data peminjaman ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endif
                            @endif
                            @endauth
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">
                            <i class="bi bi-journal fs-3 d-block mb-2"></i>
                            Belum ada data peminjaman
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Pagination --}}
<div class="mt-3">
    {{ $dtPeminjaman->links() }}
</div>
@endsection
