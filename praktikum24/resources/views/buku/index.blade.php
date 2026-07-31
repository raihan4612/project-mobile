@extends('layouts.app')

@section('title', 'Data Buku')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-0 fw-bold"><i class="bi bi-book-fill text-primary me-2"></i>Data Buku</h4>
        <small class="text-muted">Total: {{ $dtBuku->total() }} buku</small>
    </div>
    @auth
        @if(auth()->user()->isAdmin() || auth()->user()->role === 'petugas')
        <a href="{{ route('buku.create') }}" class="btn btn-primary mt-2 mt-md-0">
            <i class="bi bi-plus-circle me-1"></i> Tambah Buku
        </a>
        @endif
    @else
        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm mt-2 mt-md-0">
            <i class="bi bi-box-arrow-in-right me-1"></i> Login untuk kelola data
        </a>
    @endauth
</div>

{{-- Search & Filter --}}
<form method="GET" action="{{ route('buku.index') }}" class="row g-2 mb-3">
    <div class="col-md-4">
        <div class="input-group input-group-sm">
            <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
            <input type="text" name="search" class="form-control" placeholder="Cari judul, pengarang, penerbit..." value="{{ request('search') }}">
        </div>
    </div>
    <div class="col-md-2">
        <select name="kategori" class="form-select form-select-sm">
            <option value="">Semua Kategori</option>
            @php
                $kategoris = \App\Models\Buku::select('kategori')->distinct()->pluck('kategori');
            @endphp
            @foreach($kategoris as $k)
                <option value="{{ $k }}" {{ request('kategori') == $k ? 'selected' : '' }}>{{ $k }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <select name="status" class="form-select form-select-sm">
            <option value="">Semua Status</option>
            <option value="Tersedia" {{ request('status') == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
            <option value="Habis" {{ request('status') == 'Habis' ? 'selected' : '' }}>Habis</option>
        </select>
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-sm btn-primary w-100"><i class="bi bi-filter"></i> Filter</button>
    </div>
    <div class="col-md-2">
        <a href="{{ route('buku.index') }}" class="btn btn-sm btn-outline-secondary w-100"><i class="bi bi-arrow-counterclockwise"></i> Reset</a>
    </div>
</form>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width:4%">#</th>
                        <th>Kode</th>
                        <th>Judul Buku</th>
                        <th>Pengarang</th>
                        <th>Kategori</th>
                        <th>Stok</th>
                        <th>Tersedia</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($dtBuku as $item)
                    <tr>
                        <td class="text-muted">{{ $dtBuku->firstItem() + $loop->index }}</td>
                        <td><span class="fw-semibold text-primary">{{ $item->kode_buku }}</span></td>
                        <td>
                            <div class="fw-semibold">{{ $item->judul }}</div>
                            <small class="text-muted">{{ $item->penerbit }}, {{ $item->tahun_terbit }}</small>
                        </td>
                        <td>{{ $item->pengarang }}</td>
                        <td><span class="badge bg-secondary">{{ $item->kategori }}</span></td>
                        <td class="text-center">{{ $item->jumlah_stok }}</td>
                        <td class="text-center">
                            <span class="fw-bold {{ $item->jumlah_tersedia > 0 ? 'text-success' : 'text-danger' }}">
                                {{ $item->jumlah_tersedia }}
                            </span>
                        </td>
                        <td>
                            @if($item->status === 'Tersedia')
                                <span class="badge bg-success">Tersedia</span>
                            @else
                                <span class="badge bg-danger">Habis</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @auth
                            <a href="{{ route('buku.show', $item->id) }}"
                               class="btn btn-sm btn-outline-info" title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>

                            @if(auth()->user()->isAdmin() || auth()->user()->role === 'petugas')
                            <a href="{{ route('buku.edit', $item->id) }}"
                               class="btn btn-sm btn-outline-warning" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('buku.destroy', $item->id) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Yakin hapus buku {{ $item->judul }}?')">
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
                            <i class="bi bi-book fs-3 d-block mb-2"></i>
                            Belum ada data buku
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
    {{ $dtBuku->links() }}
</div>
@endsection
