@extends('layouts.app')

@section('title', 'Hak Akses')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0 fw-bold"><i class="bi bi-shield-lock-fill text-primary me-2"></i>Manajemen Hak Akses</h4>
    <a href="{{ route('create-hak-akses') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i> Tambah Role
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width:4%">#</th>
                        <th>Nama Role</th>
                        <th>Deskripsi</th>
                        <th class="text-center">Create</th>
                        <th class="text-center">Read</th>
                        <th class="text-center">Update</th>
                        <th class="text-center">Delete</th>
                        <th class="text-center">Export</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roles as $role)
                    <tr>
                        <td class="text-muted">{{ $loop->iteration }}</td>
                        <td><span class="fw-semibold">{{ $role->nama_role }}</span></td>
                        <td><small class="text-muted">{{ $role->deskripsi ?? '-' }}</small></td>
                        @foreach(['can_create','can_read','can_update','can_delete','can_export'] as $perm)
                        <td class="text-center">
                            @if($role->$perm)
                                <i class="bi bi-check-circle-fill text-success"></i>
                            @else
                                <i class="bi bi-x-circle-fill text-danger"></i>
                            @endif
                        </td>
                        @endforeach
                        <td class="text-center">
                            <span class="badge {{ $role->is_active ? 'bg-success' : 'bg-secondary' }}">
                                {{ $role->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('edit-hak-akses', $role->id) }}"
                               class="btn btn-sm btn-outline-warning" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('hapus-hak-akses', $role->id) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Yakin hapus role {{ $role->nama_role }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                            Belum ada data hak akses
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
