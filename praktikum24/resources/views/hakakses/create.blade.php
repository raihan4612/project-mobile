@extends('layouts.app')

@section('title', 'Tambah Hak Akses')

@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('hak-akses') }}" class="btn btn-outline-secondary btn-sm me-3">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
    <h4 class="mb-0 fw-bold"><i class="bi bi-shield-plus text-primary me-2"></i>Tambah Hak Akses</h4>
</div>

<div class="card" style="max-width: 680px;">
    <div class="card-header bg-primary text-white">
        <i class="bi bi-key-fill me-2"></i>Form Tambah Hak Akses
    </div>
    <div class="card-body">
        <form action="{{ route('simpan-hak-akses') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Role <span class="text-danger">*</span></label>
                <input type="text" name="nama_role"
                       class="form-control @error('nama_role') is-invalid @enderror"
                       value="{{ old('nama_role') }}"
                       placeholder="Contoh: Admin, Mahasiswa, Dosen">
                @error('nama_role')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Deskripsi</label>
                <textarea name="deskripsi" rows="2"
                          class="form-control @error('deskripsi') is-invalid @enderror"
                          placeholder="Penjelasan singkat tentang role ini">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <label class="form-label fw-semibold">Izin / Permission</label>
            <div class="row g-3 mb-3">
                <div class="col-6 col-md-4">
                    <div class="form-check form-switch border rounded p-3 ps-5">
                        <input class="form-check-input" type="checkbox" name="can_create" id="can_create"
                               {{ old('can_create') ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="can_create">
                            <i class="bi bi-plus-circle text-success me-1"></i> Create
                        </label>
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <div class="form-check form-switch border rounded p-3 ps-5">
                        <input class="form-check-input" type="checkbox" name="can_read" id="can_read"
                               {{ old('can_read') ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="can_read">
                            <i class="bi bi-eye text-info me-1"></i> Read
                        </label>
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <div class="form-check form-switch border rounded p-3 ps-5">
                        <input class="form-check-input" type="checkbox" name="can_update" id="can_update"
                               {{ old('can_update') ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="can_update">
                            <i class="bi bi-pencil text-warning me-1"></i> Update
                        </label>
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <div class="form-check form-switch border rounded p-3 ps-5">
                        <input class="form-check-input" type="checkbox" name="can_delete" id="can_delete"
                               {{ old('can_delete') ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="can_delete">
                            <i class="bi bi-trash text-danger me-1"></i> Delete
                        </label>
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <div class="form-check form-switch border rounded p-3 ps-5">
                        <input class="form-check-input" type="checkbox" name="can_export" id="can_export"
                               {{ old('can_export') ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="can_export">
                            <i class="bi bi-download text-secondary me-1"></i> Export
                        </label>
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <div class="form-check form-switch border rounded p-3 ps-5">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                               {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="is_active">
                            <i class="bi bi-toggle-on text-primary me-1"></i> Aktif
                        </label>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-save me-1"></i> Simpan
                </button>
                <a href="{{ route('hak-akses') }}" class="btn btn-outline-secondary px-4">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection