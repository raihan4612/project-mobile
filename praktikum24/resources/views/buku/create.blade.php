@extends('layouts.app')

@section('title', 'Tambah Buku')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0 fw-bold"><i class="bi bi-book-fill text-primary me-2"></i>Tambah Buku</h4>
    <a href="{{ route('buku.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="card">
    <div class="card-header bg-primary text-white">
        <i class="bi bi-plus-circle me-2"></i>Form Tambah Buku
    </div>
    <div class="card-body">
        <form action="{{ route('buku.store') }}" method="POST">
            @csrf

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Kode Buku <span class="text-danger">*</span></label>
                    <input type="text" name="kode_buku" class="form-control @error('kode_buku') is-invalid @enderror"
                           value="{{ old('kode_buku') }}" placeholder="Contoh: BK-001">
                    @error('kode_buku')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-8">
                    <label class="form-label fw-semibold">Judul Buku <span class="text-danger">*</span></label>
                    <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror"
                           value="{{ old('judul') }}" placeholder="Judul lengkap buku">
                    @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Pengarang <span class="text-danger">*</span></label>
                    <input type="text" name="pengarang" class="form-control @error('pengarang') is-invalid @enderror"
                           value="{{ old('pengarang') }}" placeholder="Nama pengarang">
                    @error('pengarang')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Penerbit <span class="text-danger">*</span></label>
                    <input type="text" name="penerbit" class="form-control @error('penerbit') is-invalid @enderror"
                           value="{{ old('penerbit') }}" placeholder="Nama penerbit">
                    @error('penerbit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tahun Terbit <span class="text-danger">*</span></label>
                    <input type="text" name="tahun_terbit" class="form-control @error('tahun_terbit') is-invalid @enderror"
                           value="{{ old('tahun_terbit') }}" placeholder="2024" maxlength="4">
                    @error('tahun_terbit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                    <select name="kategori" class="form-select @error('kategori') is-invalid @enderror">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach(['Fiksi','Non-Fiksi','Sains','Teknologi','Matematika','Sejarah','Biografi','Filsafat','Bahasa','Agama','Hukum','Ekonomi','Kesehatan','Lainnya'] as $kat)
                            <option value="{{ $kat }}" {{ old('kategori') == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                        @endforeach
                    </select>
                    @error('kategori')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Jumlah Stok <span class="text-danger">*</span></label>
                    <input type="number" name="jumlah_stok" class="form-control @error('jumlah_stok') is-invalid @enderror"
                           value="{{ old('jumlah_stok', 1) }}" min="1">
                    @error('jumlah_stok')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-12">
                    <label class="form-label fw-semibold">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror"
                              rows="3" placeholder="Deskripsi singkat buku (opsional)">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <hr class="mt-4">
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i> Simpan Buku
                </button>
                <a href="{{ route('buku.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
