@extends('layouts.app')

@section('title', 'Data Petugas')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-0 fw-bold">
            <i class="bi bi-person-workspace text-primary me-2"></i>Data Petugas
        </h4>
        <small class="text-muted">
            Total: {{ $dtPetugas->total() }} petugas
        </small>
    </div>

    <a href="{{ route('petugas.create') }}" class="btn btn-primary mt-2 mt-md-0">
        <i class="bi bi-plus-circle me-1"></i> Tambah Petugas
    </a>
</div>

{{-- Search & Filter --}}
<form method="GET" action="{{ route('petugas.index') }}" class="row g-2 mb-3">
    <div class="col-md-5">
        <div class="input-group input-group-sm">
            <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
            <input type="text" name="search" class="form-control" placeholder="Cari nama, kode, email, jabatan..." value="{{ request('search') }}">
        </div>
    </div>
    <div class="col-md-3">
        <select name="status" class="form-select form-select-sm">
            <option value="">Semua Status</option>
            <option value="Aktif" {{ request('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
            <option value="Nonaktif" {{ request('status') == 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
        </select>
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-sm btn-primary w-100"><i class="bi bi-filter"></i> Filter</button>
    </div>
    <div class="col-md-2">
        <a href="{{ route('petugas.index') }}" class="btn btn-sm btn-outline-secondary w-100"><i class="bi bi-arrow-counterclockwise"></i> Reset</a>
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
                        <th>Nama</th>
                        <th>Email</th>
                        <th>No HP</th>
                        <th>Jabatan</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($dtPetugas as $item)
                    <tr>
                        <td class="text-muted">{{ $dtPetugas->firstItem() + $loop->index }}</td>
                        <td>
                            <span class="fw-semibold">
                                {{ $item->kode_petugas }}
                            </span>
                        </td>
                        <td>{{ $item->nama }}</td>
                        <td>{{ $item->email }}</td>
                        <td>{{ $item->no_hp }}</td>
                        <td>{{ $item->jabatan }}</td>

                        <td>
                            <span class="badge {{ $item->status == 'Aktif' ? 'bg-success' : 'bg-secondary' }}">
                                {{ $item->status }}
                            </span>
                        </td>

                        <td class="text-center">
                            <a href="{{ route('petugas.show', $item->id) }}"
                               class="btn btn-sm btn-outline-info">
                                <i class="bi bi-eye"></i>
                            </a>

                            <a href="{{ route('petugas.edit', $item->id) }}"
                               class="btn btn-sm btn-outline-warning">
                                <i class="bi bi-pencil"></i>
                            </a>

                            <form action="{{ route('petugas.destroy', $item->id) }}"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('Yakin hapus data {{ $item->nama }}?')">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                            Belum ada data petugas
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
    {{ $dtPetugas->links() }}
</div>
@endsection