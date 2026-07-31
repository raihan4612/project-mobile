@extends('layouts.app')

@section('title', 'Tambah Pengajuan Beasiswa')

@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('beasiswa.index') }}" class="btn btn-outline-secondary btn-sm me-3">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
    <h4 class="mb-0 fw-bold"><i class="bi bi-file-text-fill text-primary me-2"></i>Tambah Pengajuan Beasiswa</h4>
</div>

<form action="{{ route('beasiswa.store') }}" method="POST">
    @csrf
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <i class="bi bi-info-circle me-2"></i>Data Pengajuan
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
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

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Program Beasiswa <span class="text-danger">*</span></label>
                    <select name="program_beasiswa_id" class="form-select @error('program_beasiswa_id') is-invalid @enderror">
                        <option value="">-- Pilih Program --</option>
                        @foreach($programBeasiswaList as $prog)
                        <option value="{{ $prog->id }}" {{ old('program_beasiswa_id') == $prog->id ? 'selected' : '' }}>
                            {{ $prog->nama_beasiswa }} — {{ $prog->penyelenggara }}
                        </option>
                        @endforeach
                    </select>
                    @error('program_beasiswa_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tanggal Pengajuan <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_pengajuan" class="form-control @error('tanggal_pengajuan') is-invalid @enderror"
                           value="{{ old('tanggal_pengajuan', date('Y-m-d')) }}">
                    @error('tanggal_pengajuan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                        <option value="Diajukan" {{ old('status', 'Diajukan') == 'Diajukan' ? 'selected' : '' }}>Diajukan</option>
                        <option value="Disetujui" {{ old('status') == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="Ditolak" {{ old('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Keterangan</label>
                    <textarea name="keterangan" rows="3" class="form-control @error('keterangan') is-invalid @enderror"
                              placeholder="Catatan tambahan (opsional)">{{ old('keterangan') }}</textarea>
                    @error('keterangan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary px-4">
            <i class="bi bi-save me-1"></i> Simpan
        </button>
        <a href="{{ route('beasiswa.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
    </div>
</form>
@endsection
