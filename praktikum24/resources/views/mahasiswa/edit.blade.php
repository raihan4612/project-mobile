@extends('layouts.app')

@section('title', 'Edit Mahasiswa')

@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('data-mahasiswa') }}" class="btn btn-outline-secondary btn-sm me-3">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
    <h4 class="mb-0 fw-bold"><i class="bi bi-pencil-square text-warning me-2"></i>Edit Data Mahasiswa</h4>
</div>

<form action="{{ route('update-mahasiswa', $dtMhs->id) }}" method="POST">
    @csrf
    @method('PUT')

    {{-- ── IDENTITAS PRIBADI ─────────────────────────────────────── --}}
    <div class="card mb-4">
        <div class="card-header bg-warning text-dark">
            <i class="bi bi-person-badge me-2"></i>Identitas Pribadi
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">NIM <span class="text-danger">*</span></label>
                    <input type="text" name="nim" class="form-control @error('nim') is-invalid @enderror"
                           value="{{ old('nim', $dtMhs->nim) }}">
                    @error('nim') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-8">
                    <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                           value="{{ old('nama', $dtMhs->nama) }}">
                    @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Jenis Kelamin <span class="text-danger">*</span></label>
                    <select name="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror">
                        <option value="L" {{ old('jenis_kelamin', $dtMhs->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin', $dtMhs->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tempat Lahir <span class="text-danger">*</span></label>
                    <input type="text" name="tempat_lahir" class="form-control @error('tempat_lahir') is-invalid @enderror"
                           value="{{ old('tempat_lahir', $dtMhs->tempat_lahir) }}">
                    @error('tempat_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tanggal Lahir <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_lahir" class="form-control @error('tanggal_lahir') is-invalid @enderror"
                           value="{{ old('tanggal_lahir', $dtMhs->tanggal_lahir) }}">
                    @error('tanggal_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>
    </div>

    {{-- ── KONTAK & ALAMAT ───────────────────────────────────────── --}}
    <div class="card mb-4">
        <div class="card-header bg-warning text-dark">
            <i class="bi bi-geo-alt-fill me-2"></i>Kontak & Alamat
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">No. HP <span class="text-danger">*</span></label>
                    <input type="text" name="no_hp" class="form-control @error('no_hp') is-invalid @enderror"
                           value="{{ old('no_hp', $dtMhs->no_hp) }}">
                    @error('no_hp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email', $dtMhs->email) }}">
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Alamat Lengkap <span class="text-danger">*</span></label>
                    <textarea name="alamat" rows="2" class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat', $dtMhs->alamat) }}</textarea>
                    @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Kota <span class="text-danger">*</span></label>
                    <input type="text" name="kota" class="form-control @error('kota') is-invalid @enderror"
                           value="{{ old('kota', $dtMhs->kota) }}">
                    @error('kota') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Provinsi <span class="text-danger">*</span></label>
                    <input type="text" name="provinsi" class="form-control @error('provinsi') is-invalid @enderror"
                           value="{{ old('provinsi', $dtMhs->provinsi) }}">
                    @error('provinsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Kode Pos</label>
                    <input type="text" name="kode_pos" class="form-control @error('kode_pos') is-invalid @enderror"
                           value="{{ old('kode_pos', $dtMhs->kode_pos) }}">
                    @error('kode_pos') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

            </div>
        </div>
    </div>

    {{-- ── DATA AKADEMIK ─────────────────────────────────────────── --}}
    <div class="card mb-4">
        <div class="card-header bg-warning text-dark">
            <i class="bi bi-book-fill me-2"></i>Data Akademik
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Program Studi <span class="text-danger">*</span></label>
                    <input type="text" name="prodi" class="form-control @error('prodi') is-invalid @enderror"
                           value="{{ old('prodi', $dtMhs->prodi) }}">
                    @error('prodi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Fakultas <span class="text-danger">*</span></label>
                    <input type="text" name="fakultas" class="form-control @error('fakultas') is-invalid @enderror"
                           value="{{ old('fakultas', $dtMhs->fakultas) }}">
                    @error('fakultas') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Semester <span class="text-danger">*</span></label>
                    <input type="number" name="semester" min="1" max="14"
                           class="form-control @error('semester') is-invalid @enderror"
                           value="{{ old('semester', $dtMhs->semester) }}">
                    @error('semester') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">IPK</label>
                    <input type="number" name="ipk" step="0.01" min="0" max="4"
                           class="form-control @error('ipk') is-invalid @enderror"
                           value="{{ old('ipk', $dtMhs->ipk) }}" placeholder="0.00 – 4.00">
                    @error('ipk') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tahun Masuk <span class="text-danger">*</span></label>
                    <input type="text" name="tahun_masuk" maxlength="4"
                           class="form-control @error('tahun_masuk') is-invalid @enderror"
                           value="{{ old('tahun_masuk', $dtMhs->tahun_masuk) }}">
                    @error('tahun_masuk') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                        @foreach(['Aktif','Cuti','Lulus','Dropout'] as $s)
                        <option value="{{ $s }}" {{ old('status', $dtMhs->status) == $s ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
            </div>
        </div>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-warning px-4">
            <i class="bi bi-save me-1"></i> Simpan Perubahan
        </button>
        <a href="{{ route('data-mahasiswa') }}" class="btn btn-outline-secondary px-4">Batal</a>
    </div>
</form>
@endsection
