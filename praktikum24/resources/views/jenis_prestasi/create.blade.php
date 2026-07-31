@extends('layouts.app')

@section('title', 'Tambah Jenis Prestasi')

@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('jenis-prestasi.index') }}" class="btn btn-outline-secondary btn-sm me-3">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
    <h4 class="mb-0 fw-bold"><i class="bi bi-tag-fill text-primary me-2"></i>Tambah Jenis Prestasi</h4>
</div>

<form action="{{ route('jenis-prestasi.store') }}" method="POST">
    @csrf
    <div class="card">
        <div class="card-header bg-primary text-white">
            <i class="bi bi-info-circle me-2"></i>Data Jenis Prestasi
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Jenis <span class="text-danger">*</span></label>
                <input type="text" name="nama_jenis" class="form-control @error('nama_jenis') is-invalid @enderror"
                       value="{{ old('nama_jenis') }}" placeholder="Contoh: Akademik, Olahraga, Seni">
                @error('nama_jenis') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
    </div>

    <div class="d-flex gap-2 mt-3">
        <button type="submit" class="btn btn-primary px-4">
            <i class="bi bi-save me-1"></i> Simpan
        </button>
        <a href="{{ route('jenis-prestasi.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
    </div>
</form>
@endsection
