@extends('layouts.app')

@section('title', 'Prestasi Mahasiswa')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-0 fw-bold"><i class="bi bi-trophy-fill text-primary me-2"></i>Prestasi Mahasiswa</h4>
        <small class="text-muted">Total: {{ $dtPrestasi->total() }} prestasi</small>
    </div>

    @auth
        @if(auth()->user()->isAdmin() || auth()->user()->role === 'user')
        <a href="{{ route('prestasi.create') }}" class="btn btn-primary mt-2 mt-md-0">
            <i class="bi bi-plus-circle me-1"></i> Tambah Prestasi
        </a>
        @endif
    @else
        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm mt-2 mt-md-0">
            <i class="bi bi-box-arrow-in-right me-1"></i> Login untuk tambah prestasi
        </a>
    @endauth
</div>

{{-- Search & Filter --}}
<form method="GET" action="{{ route('prestasi.index') }}" class="row g-2 mb-3">
    <div class="col-md-4">
        <div class="input-group input-group-sm">
            <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
            <input type="text" name="search" class="form-control" placeholder="Cari nama lomba, mahasiswa..." value="{{ request('search') }}">
        </div>
    </div>
    <div class="col-md-2">
        <select name="jenis_id" class="form-select form-select-sm">
            <option value="">Semua Jenis</option>
            @foreach(\App\Models\JenisPrestasi::orderBy('nama_jenis')->get() as $j)
                <option value="{{ $j->id }}" {{ request('jenis_id') == $j->id ? 'selected' : '' }}>{{ $j->nama_jenis }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <select name="tingkat_id" class="form-select form-select-sm">
            <option value="">Semua Tingkat</option>
            @foreach(\App\Models\TingkatPrestasi::orderBy('id')->get() as $t)
                <option value="{{ $t->id }}" {{ request('tingkat_id') == $t->id ? 'selected' : '' }}>{{ $t->nama_tingkat }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <select name="status" class="form-select form-select-sm">
            <option value="">Semua Status</option>
            <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
            <option value="Disetujui" {{ request('status') == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
            <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
        </select>
    </div>
    <div class="col-md-1">
        <button type="submit" class="btn btn-sm btn-primary w-100"><i class="bi bi-filter"></i></button>
    </div>
    <div class="col-md-1">
        <a href="{{ route('prestasi.index') }}" class="btn btn-sm btn-outline-secondary w-100"><i class="bi bi-arrow-counterclockwise"></i></a>
    </div>
</form>

{{-- Ringkasan Status --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0" style="background: linear-gradient(135deg,#fff3cd,#ffe08a);">
            <div class="card-body d-flex align-items-center gap-3">
                <i class="bi bi-hourglass-split fs-2 text-warning"></i>
                <div>
                    <div class="fs-4 fw-bold">{{ $totalPending }}</div>
                    <small class="text-muted">Menunggu Verifikasi</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0" style="background: linear-gradient(135deg,#d1fae5,#a7f3d0);">
            <div class="card-body d-flex align-items-center gap-3">
                <i class="bi bi-check-circle-fill fs-2 text-success"></i>
                <div>
                    <div class="fs-4 fw-bold">{{ $totalDisetujui }}</div>
                    <small class="text-muted">Disetujui</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0" style="background: linear-gradient(135deg,#fee2e2,#fca5a5);">
            <div class="card-body d-flex align-items-center gap-3">
                <i class="bi bi-x-circle-fill fs-2 text-danger"></i>
                <div>
                    <div class="fs-4 fw-bold">{{ $totalDitolak }}</div>
                    <small class="text-muted">Ditolak</small>
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
                        <th>Mahasiswa</th>
                        <th>Lomba</th>
                        <th>Jenis</th>
                        <th>Tingkat</th>
                        <th>Juara</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($dtPrestasi as $item)
                    <tr>
                        <td class="text-muted">{{ $dtPrestasi->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="fw-semibold">{{ $item->mahasiswa->nama }}</div>
                            <small class="text-muted">{{ $item->mahasiswa->nim }}</small>
                        </td>
                        <td>{{ Str::limit($item->nama_lomba, 35) }}</td>
                        <td><span class="badge bg-secondary">{{ $item->jenis->nama_jenis }}</span></td>
                        <td><span class="badge bg-info text-dark">{{ $item->tingkat->nama_tingkat }}</span></td>
                        <td>{{ $item->juara ?? '-' }}</td>
                        <td>{{ $item->tanggal->format('d/m/Y') }}</td>
                        <td>
                            @php
                                $badge = match($item->status_verifikasi) {
                                    'Disetujui' => 'bg-success',
                                    'Ditolak'   => 'bg-danger',
                                    default     => 'bg-warning text-dark',
                                };
                            @endphp
                            <span class="badge {{ $badge }}">{{ $item->status_verifikasi }}</span>
                        </td>
                        <td class="text-center">
                            @auth
                            <a href="{{ route('prestasi.show', $item->id) }}"
                               class="btn btn-sm btn-outline-info" title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>

                            @if(auth()->user()->isAdmin() || auth()->user()->role === 'user')
                            <a href="{{ route('prestasi.edit', $item->id) }}"
                               class="btn btn-sm btn-outline-warning" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @endif

                            @if(auth()->user()->isAdmin())
                            <form action="{{ route('prestasi.destroy', $item->id) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Yakin hapus data prestasi ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                            @endauth
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">
                            <i class="bi bi-trophy fs-3 d-block mb-2"></i>
                            Belum ada data prestasi
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
    {{ $dtPrestasi->links() }}
</div>
@endsection
