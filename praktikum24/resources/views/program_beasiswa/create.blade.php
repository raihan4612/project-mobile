@extends('layouts.app')

@section('title', 'Tambah Program Beasiswa')

@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('program-beasiswa.index') }}" class="btn btn-outline-secondary btn-sm me-3">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
    <h4 class="mb-0 fw-bold"><i class="bi bi-award-fill text-primary me-2"></i>Tambah Program Beasiswa</h4>
</div>

<form action="{{ route('program-beasiswa.store') }}" method="POST">
    @csrf
    <div class="card">
        <div class="card-header bg-primary text-white">
            <i class="bi bi-info-circle me-2"></i>Data Program
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label fw-semibold">Nama Beasiswa <span class="text-danger">*</span></label>
                    <input type="text" name="nama_beasiswa" class="form-control @error('nama_beasiswa') is-invalid @enderror"
                           value="{{ old('nama_beasiswa') }}" placeholder="Contoh: Beasiswa Prestasi Akademik">
                    @error('nama_beasiswa') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Penyelenggara <span class="text-danger">*</span></label>
                    <input type="text" name="penyelenggara" class="form-control @error('penyelenggara') is-invalid @enderror"
                           value="{{ old('penyelenggara') }}" placeholder="Nama penyelenggara">
                    @error('penyelenggara') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tahun Akademik <span class="text-danger">*</span></label>
                    <input type="text" name="tahun_akademik" class="form-control @error('tahun_akademik') is-invalid @enderror"
                           value="{{ old('tahun_akademik') }}" placeholder="Contoh: 2024/2025">
                    @error('tahun_akademik') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Jumlah Dana <span class="text-danger">*</span></label>
                    <input type="number" name="jumlah_dana" step="0.01" min="0"
                           class="form-control @error('jumlah_dana') is-invalid @enderror"
                           value="{{ old('jumlah_dana') }}" placeholder="0">
                    @error('jumlah_dana') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2 mt-3">
        <button type="submit" class="btn btn-primary px-4">
            <i class="bi bi-save me-1"></i> Simpan
        </button>
        <a href="{{ route('program-beasiswa.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
    </div>
</form>
@endsection
