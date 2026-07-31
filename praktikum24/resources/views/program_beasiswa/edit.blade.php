@extends('layouts.app')

@section('title', 'Edit Program Beasiswa')

@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('program-beasiswa.index') }}" class="btn btn-outline-secondary btn-sm me-3">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
    <h4 class="mb-0 fw-bold"><i class="bi bi-pencil-square text-warning me-2"></i>Edit Program Beasiswa</h4>
</div>

<form action="{{ route('program-beasiswa.update', $program->id) }}" method="POST">
    @csrf @method('PUT')
    <div class="card">
        <div class="card-header bg-warning text-dark">
            <i class="bi bi-info-circle me-2"></i>Data Program
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label fw-semibold">Nama Beasiswa <span class="text-danger">*</span></label>
                    <input type="text" name="nama_beasiswa" class="form-control @error('nama_beasiswa') is-invalid @enderror"
                           value="{{ old('nama_beasiswa', $program->nama_beasiswa) }}">
                    @error('nama_beasiswa') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Penyelenggara <span class="text-danger">*</span></label>
                    <input type="text" name="penyelenggara" class="form-control @error('penyelenggara') is-invalid @enderror"
                           value="{{ old('penyelenggara', $program->penyelenggara) }}">
                    @error('penyelenggara') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tahun Akademik <span class="text-danger">*</span></label>
                    <input type="text" name="tahun_akademik" class="form-control @error('tahun_akademik') is-invalid @enderror"
                           value="{{ old('tahun_akademik', $program->tahun_akademik) }}">
                    @error('tahun_akademik') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Jumlah Dana <span class="text-danger">*</span></label>
                    <input type="number" name="jumlah_dana" step="0.01" min="0"
                           class="form-control @error('jumlah_dana') is-invalid @enderror"
                           value="{{ old('jumlah_dana', $program->jumlah_dana) }}">
                    @error('jumlah_dana') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2 mt-3">
        <button type="submit" class="btn btn-warning px-4">
            <i class="bi bi-save me-1"></i> Simpan Perubahan
        </button>
        <a href="{{ route('program-beasiswa.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
    </div>
</form>
@endsection
