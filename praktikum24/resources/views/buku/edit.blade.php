@extends('layouts.app')

@section('title', 'Edit Buku')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0 fw-bold"><i class="bi bi-book-fill text-primary me-2"></i>Edit Buku</h4>
    <a href="{{ route('buku.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="card">
    <div class="card-header bg-warning text-dark">
        <i class="bi bi-pencil me-2"></i>Form Edit Buku
    </div>
    <div class="card-body">
        <form action="{{ route('buku.update', $buku->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Kode Buku <span class="text-danger">*</span></label>
                    <input type="text" name="kode_buku" class="form-control @error('kode_buku') is-invalid @enderror"
                           value="{{ old('kode_buku', $buku->kode_buku) }}">
                    @error('kode_buku')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-8">
                    <label class="form-label fw-semibold">Judul Buku <span class="text-danger">*</span></label>
                    <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror"
                           value="{{ old('judul', $buku->judul) }}">
                    @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Pengarang <span class="text-danger">*</span></label>
                    <input type="text" name="pengarang" class="form-control @error('pengarang') is-invalid @enderror"
                           value="{{ old('pengarang', $buku->pengarang) }}">
                    @error('pengarang')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Penerbit <span class="text-danger">*</span></label>
                    <input type="text" name="penerbit" class="form-control @error('penerbit') is-invalid @enderror"
                           value="{{ old('penerbit', $buku->penerbit) }}">
                    @error('penerbit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tahun Terbit <span class="text-danger">*</span></label>
                    <input type="text" name="tahun_terbit" class="form-control @error('tahun_terbit') is-invalid @enderror"
                           value="{{ old('tahun_terbit', $buku->tahun_terbit) }}" maxlength="4">
                    @error('tahun_terbit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                    <select name="kategori" class="form-select @error('kategori') is-invalid @enderror">
                        @foreach(['Fiksi','Non-Fiksi','Sains','Teknologi','Matematika','Sejarah','Biografi','Filsafat','Bahasa','Agama','Hukum','Ekonomi','Kesehatan','Lainnya'] as $kat)
                            <option value="{{ $kat }}" {{ (old('kategori', $buku->kategori)) == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                        @endforeach
                    </select>
                    @error('kategori')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Jumlah Stok <span class="text-danger">*</span></label>
                    <input type="number" name="jumlah_stok" class="form-control @error('jumlah_stok') is-invalid @enderror"
                           value="{{ old('jumlah_stok', $buku->jumlah_stok) }}" min="1">
                    <small class="text-muted">Saat ini tersedia: {{ $buku->jumlah_tersedia }}</small>
                    @error('jumlah_stok')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-12">
                    <label class="form-label fw-semibold">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror"
                              rows="3">{{ old('deskripsi', $buku->deskripsi) }}</textarea>
                    @error('deskripsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <hr class="mt-4">
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-warning text-dark">
                    <i class="bi bi-save me-1"></i> Update Buku
                </button>
                <a href="{{ route('buku.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
