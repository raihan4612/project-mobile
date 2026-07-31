@extends('layouts.app')

@section('title', 'Edit Petugas')

@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('petugas.index') }}"
       class="btn btn-outline-secondary btn-sm me-3">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>

    <h4 class="mb-0 fw-bold">
        <i class="bi bi-pencil-square text-warning me-2"></i>
        Edit Data Petugas
    </h4>
</div>

<form action="{{ route('petugas.update', $petugas->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="card mb-4">
        <div class="card-header bg-warning text-dark">
            <i class="bi bi-person-badge me-2"></i>Data Petugas
        </div>

        <div class="card-body">
            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nama</label>

                    <input type="text"
                           name="nama"
                           class="form-control"
                           value="{{ old('nama', $petugas->nama) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Email</label>

                    <input type="email"
                           name="email"
                           class="form-control"
                           value="{{ old('email', $petugas->email) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">No HP</label>

                    <input type="text"
                           name="no_hp"
                           class="form-control"
                           value="{{ old('no_hp', $petugas->no_hp) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Jabatan</label>

                    <input type="text"
                           name="jabatan"
                           class="form-control"
                           value="{{ old('jabatan', $petugas->jabatan) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Status</label>

                    <select name="status" class="form-select">
                        <option value="Aktif"
                            {{ old('status', $petugas->status) == 'Aktif' ? 'selected' : '' }}>
                            Aktif
                        </option>

                        <option value="Nonaktif"
                            {{ old('status', $petugas->status) == 'Nonaktif' ? 'selected' : '' }}>
                            Nonaktif
                        </option>
                    </select>
                </div>

            </div>
        </div>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-warning px-4">
            <i class="bi bi-save me-1"></i> Simpan Perubahan
        </button>

        <a href="{{ route('petugas.index') }}"
           class="btn btn-outline-secondary px-4">
            Batal
        </a>
    </div>
</form>
@endsection