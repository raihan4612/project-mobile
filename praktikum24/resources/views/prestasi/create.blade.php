@extends('layouts.app')

@section('title', 'Tambah Prestasi')

@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('prestasi.index') }}" class="btn btn-outline-secondary btn-sm me-3">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
    <h4 class="mb-0 fw-bold"><i class="bi bi-trophy-fill text-primary me-2"></i>Tambah Prestasi Mahasiswa</h4>
</div>

<form action="{{ route('prestasi.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <i class="bi bi-info-circle me-2"></i>Informasi Prestasi
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label fw-semibold">Mahasiswa <span class="text-danger">*</span></label>
                    <select name="mahasiswa_id" class="form-select @error('mahasiswa_id') is-invalid @enderror">
                        <option value="">-- Pilih Mahasiswa --</option>
                        @foreach($mahasiswaList as $mhs)
                        <option value="{{ $mhs->id }}" {{ old('mahasiswa_id') == $mhs->id ? 'selected' : '' }}>
                            {{ $mhs->nim }} — {{ $mhs->nama }}
                        </option>
                        @endforeach
                    </select>
                    @error('mahasiswa_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-8">
                    <label class="form-label fw-semibold">Nama Lomba / Kegiatan <span class="text-danger">*</span></label>
                    <input type="text" name="nama_lomba" class="form-control @error('nama_lomba') is-invalid @enderror"
                           value="{{ old('nama_lomba') }}" placeholder="Contoh: Olimpiade Matematika Nasional">
                    @error('nama_lomba') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tanggal <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
                           value="{{ old('tanggal') }}">
                    @error('tanggal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Jenis Prestasi <span class="text-danger">*</span></label>
                    <select name="jenis_id" class="form-select @error('jenis_id') is-invalid @enderror">
                        <option value="">-- Pilih Jenis --</option>
                        @foreach($jenisList as $j)
                        <option value="{{ $j->id }}" {{ old('jenis_id') == $j->id ? 'selected' : '' }}>
                            {{ $j->nama_jenis }}
                        </option>
                        @endforeach
                    </select>
                    @error('jenis_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tingkat <span class="text-danger">*</span></label>
                    <select name="tingkat_id" class="form-select @error('tingkat_id') is-invalid @enderror">
                        <option value="">-- Pilih Tingkat --</option>
                        @foreach($tingkatList as $t)
                        <option value="{{ $t->id }}" {{ old('tingkat_id') == $t->id ? 'selected' : '' }}>
                            {{ $t->nama_tingkat }}
                        </option>
                        @endforeach
                    </select>
                    @error('tingkat_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Juara / Peringkat</label>
                    <input type="text" name="juara" class="form-control @error('juara') is-invalid @enderror"
                           value="{{ old('juara') }}" placeholder="Contoh: Juara 1, Finalis">
                    @error('juara') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-8">
                    <label class="form-label fw-semibold">Penyelenggara <span class="text-danger">*</span></label>
                    <input type="text" name="penyelenggara" class="form-control @error('penyelenggara') is-invalid @enderror"
                           value="{{ old('penyelenggara') }}" placeholder="Nama institusi penyelenggara">
                    @error('penyelenggara') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Upload Sertifikat</label>
                    <input type="file" name="sertifikat" class="form-control @error('sertifikat') is-invalid @enderror"
                           accept=".pdf,.jpg,.jpeg,.png">
                    <small class="text-muted">Format: PDF/JPG/PNG, maks 2MB</small>
                    @error('sertifikat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-1"></i> Simpan
        </button>
        <a href="{{ route('prestasi.index') }}" class="btn btn-outline-secondary">Batal</a>
    </div>
</form>
@endsection
