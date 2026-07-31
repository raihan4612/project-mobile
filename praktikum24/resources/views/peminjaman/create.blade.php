@extends('layouts.app')

@section('title', 'Catat Peminjaman')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0 fw-bold"><i class="bi bi-journal-bookmark-fill text-primary me-2"></i>Catat Peminjaman Buku</h4>
    <a href="{{ route('peminjaman.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

@if($mahasiswaList->isEmpty())
    <div class="alert alert-warning">
        <i class="bi bi-exclamation-triangle me-2"></i>
        Belum ada mahasiswa aktif. Silakan tambah data mahasiswa terlebih dahulu.
    </div>
@elseif($bukuList->isEmpty())
    <div class="alert alert-warning">
        <i class="bi bi-exclamation-triangle me-2"></i>
        Tidak ada buku tersedia saat ini. Silakan periksa stok buku.
    </div>
@elseif($petugasList->isEmpty())
    <div class="alert alert-warning">
        <i class="bi bi-exclamation-triangle me-2"></i>
        Belum ada petugas aktif. Silakan tambah data petugas terlebih dahulu.
    </div>
@else
<div class="card">
    <div class="card-header bg-primary text-white">
        <i class="bi bi-plus-circle me-2"></i>Form Peminjaman Buku
    </div>
    <div class="card-body">
        <form action="{{ route('peminjaman.store') }}" method="POST">
            @csrf

            <div class="row g-3">
                {{-- Pilih Mahasiswa --}}
                <div class="col-md-12">
                    <label class="form-label fw-semibold">Mahasiswa Peminjam <span class="text-danger">*</span></label>
                    <select name="mahasiswa_id" id="mahasiswa_id"
                            class="form-select @error('mahasiswa_id') is-invalid @enderror"
                            onchange="tampilInfoMhs(this)">
                        <option value="">-- Pilih Mahasiswa --</option>
                        @foreach($mahasiswaList as $mhs)
                            <option value="{{ $mhs->id }}"
                                    data-nim="{{ $mhs->nim }}"
                                    data-prodi="{{ $mhs->prodi }}"
                                    data-hp="{{ $mhs->no_hp }}"
                                    {{ old('mahasiswa_id') == $mhs->id ? 'selected' : '' }}>
                                {{ $mhs->nama }} — {{ $mhs->nim }}
                            </option>
                        @endforeach
                    </select>
                    @error('mahasiswa_id')<div class="invalid-feedback">{{ $message }}</div>@enderror

                    <div id="infoMhs" class="mt-2 p-2 rounded bg-light d-none">
                        <small>
                            <span class="text-muted">NIM:</span> <strong id="mhsNim"></strong> &nbsp;|&nbsp;
                            <span class="text-muted">Prodi:</span> <strong id="mhsProdi"></strong> &nbsp;|&nbsp;
                            <span class="text-muted">HP:</span> <strong id="mhsHp"></strong>
                        </small>
                    </div>
                </div>

                {{-- Pilih Buku --}}
                <div class="col-md-12">
                    <label class="form-label fw-semibold">Buku yang Dipinjam <span class="text-danger">*</span></label>
                    <select name="buku_id" id="buku_id"
                            class="form-select @error('buku_id') is-invalid @enderror"
                            onchange="tampilInfoBuku(this)">
                        <option value="">-- Pilih Buku (hanya yang tersedia) --</option>
                        @foreach($bukuList as $buku)
                            <option value="{{ $buku->id }}"
                                    data-kode="{{ $buku->kode_buku }}"
                                    data-pengarang="{{ $buku->pengarang }}"
                                    data-tersedia="{{ $buku->jumlah_tersedia }}"
                                    {{ old('buku_id') == $buku->id ? 'selected' : '' }}>
                                {{ $buku->judul }} — {{ $buku->kode_buku }} (Tersedia: {{ $buku->jumlah_tersedia }})
                            </option>
                        @endforeach
                    </select>
                    @error('buku_id')<div class="invalid-feedback">{{ $message }}</div>@enderror

                    <div id="infoBuku" class="mt-2 p-2 rounded bg-light d-none">
                        <small>
                            <span class="text-muted">Kode:</span> <strong id="bukuKode"></strong> &nbsp;|&nbsp;
                            <span class="text-muted">Pengarang:</span> <strong id="bukuPengarang"></strong> &nbsp;|&nbsp;
                            <span class="text-muted">Stok Tersedia:</span> <strong id="bukuTersedia" class="text-success"></strong>
                        </small>
                    </div>
                </div>

                {{-- ← TAMBAHAN: Pilih Petugas --}}
                <div class="col-md-12">
                    <label class="form-label fw-semibold">Petugas yang Melayani <span class="text-danger">*</span></label>
                    <select name="petugas_id" id="petugas_id"
                            class="form-select @error('petugas_id') is-invalid @enderror"
                            onchange="tampilInfoPetugas(this)">
                        <option value="">-- Pilih Petugas --</option>
                        @foreach($petugasList as $petugas)
                            <option value="{{ $petugas->id }}"
                                    data-kode="{{ $petugas->kode_petugas }}"
                                    data-jabatan="{{ $petugas->jabatan }}"
                                    data-hp="{{ $petugas->no_hp }}"
                                    {{ old('petugas_id') == $petugas->id ? 'selected' : '' }}>
                                {{ $petugas->nama }} — {{ $petugas->kode_petugas }}
                            </option>
                        @endforeach
                    </select>
                    @error('petugas_id')<div class="invalid-feedback">{{ $message }}</div>@enderror

                    <div id="infoPetugas" class="mt-2 p-2 rounded bg-light d-none">
                        <small>
                            <span class="text-muted">Kode:</span> <strong id="petugasKode"></strong> &nbsp;|&nbsp;
                            <span class="text-muted">Jabatan:</span> <strong id="petugasJabatan"></strong> &nbsp;|&nbsp;
                            <span class="text-muted">HP:</span> <strong id="petugasHp"></strong>
                        </small>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tanggal Pinjam <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_pinjam" id="tanggal_pinjam"
                           class="form-control @error('tanggal_pinjam') is-invalid @enderror"
                           value="{{ old('tanggal_pinjam', date('Y-m-d')) }}"
                           onchange="hitungMinKembali()">
                    @error('tanggal_pinjam')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Rencana Tanggal Kembali <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_kembali_rencana" id="tanggal_kembali_rencana"
                           class="form-control @error('tanggal_kembali_rencana') is-invalid @enderror"
                           value="{{ old('tanggal_kembali_rencana') }}">
                    <small class="text-muted">Denda Rp 1.000/hari jika terlambat</small>
                    @error('tanggal_kembali_rencana')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-12">
                    <label class="form-label fw-semibold">Catatan</label>
                    <textarea name="catatan" class="form-control" rows="2"
                              placeholder="Catatan tambahan (opsional)">{{ old('catatan') }}</textarea>
                </div>
            </div>

            <hr class="mt-4">
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i> Simpan Peminjaman
                </button>
                <a href="{{ route('peminjaman.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endif
@endsection

@section('scripts')
<script>
function tampilInfoMhs(sel) {
    const opt = sel.options[sel.selectedIndex];
    if (sel.value) {
        document.getElementById('mhsNim').textContent   = opt.dataset.nim;
        document.getElementById('mhsProdi').textContent = opt.dataset.prodi;
        document.getElementById('mhsHp').textContent    = opt.dataset.hp;
        document.getElementById('infoMhs').classList.remove('d-none');
    } else {
        document.getElementById('infoMhs').classList.add('d-none');
    }
}

function tampilInfoBuku(sel) {
    const opt = sel.options[sel.selectedIndex];
    if (sel.value) {
        document.getElementById('bukuKode').textContent      = opt.dataset.kode;
        document.getElementById('bukuPengarang').textContent = opt.dataset.pengarang;
        document.getElementById('bukuTersedia').textContent  = opt.dataset.tersedia;
        document.getElementById('infoBuku').classList.remove('d-none');
    } else {
        document.getElementById('infoBuku').classList.add('d-none');
    }
}

// ← TAMBAHAN
function tampilInfoPetugas(sel) {
    const opt = sel.options[sel.selectedIndex];
    if (sel.value) {
        document.getElementById('petugasKode').textContent    = opt.dataset.kode;
        document.getElementById('petugasJabatan').textContent = opt.dataset.jabatan;
        document.getElementById('petugasHp').textContent      = opt.dataset.hp;
        document.getElementById('infoPetugas').classList.remove('d-none');
    } else {
        document.getElementById('infoPetugas').classList.add('d-none');
    }
}

function hitungMinKembali() {
    const tglPinjam = document.getElementById('tanggal_pinjam').value;
    if (tglPinjam) {
        const min = new Date(tglPinjam);
        min.setDate(min.getDate() + 1);
        document.getElementById('tanggal_kembali_rencana').min = min.toISOString().split('T')[0];
    }
}

document.addEventListener('DOMContentLoaded', function () {
    hitungMinKembali();
    const mhsSel     = document.getElementById('mahasiswa_id');
    const bukuSel    = document.getElementById('buku_id');
    const petugasSel = document.getElementById('petugas_id');
    if (mhsSel.value)     tampilInfoMhs(mhsSel);
    if (bukuSel.value)    tampilInfoBuku(bukuSel);
    if (petugasSel.value) tampilInfoPetugas(petugasSel);
});
</script>
@endsection