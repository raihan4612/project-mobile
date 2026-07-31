@extends('layouts.app')

@section('title', 'Tambah Petugas')

@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('petugas.index') }}"
       class="btn btn-outline-secondary btn-sm me-3">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>

    <h4 class="mb-0 fw-bold">
        <i class="bi bi-person-plus-fill text-primary me-2"></i>
        Tambah Data Petugas
    </h4>
</div>

<form action="{{ route('petugas.store') }}" method="POST">
    @csrf

    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <i class="bi bi-person-badge me-2"></i>Data Petugas
        </div>

        <div class="card-body">
            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label fw-semibold">
                        Nama Petugas
                    </label>

                    <input type="text"
                           name="nama"
                           class="form-control @error('nama') is-invalid @enderror"
                           value="{{ old('nama') }}"
                           placeholder="Masukkan nama petugas">

                    @error('nama')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">
                        Email
                    </label>

                    <input type="email"
                           name="email"
                           class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}"
                           placeholder="email@example.com">

                    @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">
                        No HP
                    </label>

                    <input type="text"
                           name="no_hp"
                           class="form-control @error('no_hp') is-invalid @enderror"
                           value="{{ old('no_hp') }}"
                           placeholder="08xxxxxxxxxx">

                    @error('no_hp')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">
                        Jabatan
                    </label>

                    <input type="text"
                           name="jabatan"
                           class="form-control @error('jabatan') is-invalid @enderror"
                           value="{{ old('jabatan') }}"
                           placeholder="Contoh: Admin">

                    @error('jabatan')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">
                        Status
                    </label>

                    <select name="status"
                            class="form-select @error('status') is-invalid @enderror">

                        <option value="">-- Pilih Status --</option>

                        <option value="Aktif"
                            {{ old('status') == 'Aktif' ? 'selected' : '' }}>
                            Aktif
                        </option>

                        <option value="Nonaktif"
                            {{ old('status') == 'Nonaktif' ? 'selected' : '' }}>
                            Nonaktif
                        </option>
                    </select>

                    @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>
        </div>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary px-4">
            <i class="bi bi-save me-1"></i> Simpan
        </button>

        <a href="{{ route('petugas.index') }}"
           class="btn btn-outline-secondary px-4">
            Batal
        </a>
    </div>
</form>
@endsection