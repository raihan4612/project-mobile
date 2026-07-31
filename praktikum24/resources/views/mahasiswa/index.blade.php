@extends('layouts.app')

@section('title', 'Data Mahasiswa')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-0 fw-bold"><i class="bi bi-people-fill text-primary me-2"></i>Data Mahasiswa</h4>
        <small class="text-muted">Total: {{ $dtMhs->total() }} mahasiswa</small>
    </div>
    @auth
        @if(auth()->user()->isAdmin() || auth()->user()->role === 'user')
        <a href="{{ route('create-mahasiswa') }}" class="btn btn-primary mt-2 mt-md-0">
            <i class="bi bi-plus-circle me-1"></i> Tambah Mahasiswa
        </a>
        @endif
    @else
        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm mt-2 mt-md-0">
            <i class="bi bi-box-arrow-in-right me-1"></i> Login untuk kelola data
        </a>
    @endauth
</div>

{{-- Search & Filter --}}
<form method="GET" action="{{ route('data-mahasiswa') }}" class="row g-2 mb-3">
    <div class="col-md-5">
        <div class="input-group input-group-sm">
            <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
            <input type="text" name="search" class="form-control" placeholder="Cari nama, NIM, prodi, fakultas..." value="{{ request('search') }}">
        </div>
    </div>
    <div class="col-md-3">
        <select name="status" class="form-select form-select-sm">
            <option value="">Semua Status</option>
            <option value="Aktif" {{ request('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
            <option value="Cuti" {{ request('status') == 'Cuti' ? 'selected' : '' }}>Cuti</option>
            <option value="Lulus" {{ request('status') == 'Lulus' ? 'selected' : '' }}>Lulus</option>
            <option value="Dropout" {{ request('status') == 'Dropout' ? 'selected' : '' }}>Dropout</option>
        </select>
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-sm btn-primary w-100"><i class="bi bi-filter"></i> Filter</button>
    </div>
    <div class="col-md-2">
        <a href="{{ route('data-mahasiswa') }}" class="btn btn-sm btn-outline-secondary w-100"><i class="bi bi-arrow-counterclockwise"></i> Reset</a>
    </div>
</form>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width:4%">#</th>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Prodi / Fakultas</th>
                        <th>Semester</th>
                        <th>IPK</th>
                        <th>No. HP</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($dtMhs as $item)
                    <tr>
                        <td class="text-muted">{{ $dtMhs->firstItem() + $loop->index }}</td>
                        <td><span class="fw-semibold">{{ $item->nim }}</span></td>
                        <td>{{ $item->nama }}</td>
                        <td>
                            <div>{{ $item->prodi }}</div>
                            <small class="text-muted">{{ $item->fakultas }}</small>
                        </td>
                        <td>{{ $item->semester }}</td>
                        <td>{{ $item->ipk ? number_format($item->ipk, 2) : '-' }}</td>
                        <td>{{ $item->no_hp }}</td>
                        <td>
                            @php
                                $badgeClass = match($item->status) {
                                    'Aktif'   => 'bg-success',
                                    'Cuti'    => 'bg-warning text-dark',
                                    'Lulus'   => 'bg-info text-dark',
                                    'Dropout' => 'bg-danger',
                                    default   => 'bg-secondary',
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ $item->status }}</span>
                        </td>
                        <td class="text-center">
                            @auth
                            <a href="{{ route('show-mahasiswa', $item->id) }}"
                               class="btn btn-sm btn-outline-info" title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                            @endauth

                            @auth
                                @if(auth()->user()->isAdmin() || auth()->user()->role === 'user')
                                <a href="{{ route('edit-mahasiswa', $item->id) }}"
                                   class="btn btn-sm btn-outline-warning" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @endif

                                @if(auth()->user()->isAdmin())
                                <form action="{{ route('hapus-mahasiswa', $item->id) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Yakin hapus data {{ $item->nama }}?')">
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
                            <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                            Belum ada data mahasiswa
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
    {{ $dtMhs->links() }}
</div>
@endsection
