@extends('layouts.app')

@section('title', 'Detail Pengajuan Beasiswa')

@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('beasiswa.index') }}" class="btn btn-outline-secondary btn-sm me-3">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
    <h4 class="mb-0 fw-bold"><i class="bi bi-file-text-fill text-primary me-2"></i>Detail Pengajuan Beasiswa</h4>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <i class="bi bi-info-circle me-2"></i>Informasi Pengajuan
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr>
                        <td class="text-muted fw-semibold" style="width:35%">Mahasiswa</td>
                        <td>{{ $beasiswa->mahasiswa->nama }} <small class="text-muted">({{ $beasiswa->mahasiswa->nim }})</small></td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">Program Beasiswa</td>
                        <td class="fw-semibold">{{ $beasiswa->programBeasiswa?->nama_beasiswa ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">Penyelenggara</td>
                        <td>{{ $beasiswa->programBeasiswa?->penyelenggara ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">Tahun Akademik</td>
                        <td>{{ $beasiswa->programBeasiswa?->tahun_akademik ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">Jumlah Dana</td>
                        <td class="fw-semibold">Rp {{ number_format($beasiswa->programBeasiswa?->jumlah_dana ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">Tanggal Pengajuan</td>
                        <td>{{ $beasiswa->tanggal_pengajuan->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">Status</td>
                        <td>
                            @php
                                $badge = match($beasiswa->status) {
                                    'Disetujui' => 'bg-success',
                                    'Ditolak'   => 'bg-danger',
                                    default     => 'bg-warning text-dark',
                                };
                            @endphp
                            <span class="badge {{ $badge }}">{{ $beasiswa->status }}</span>
                        </td>
                    </tr>
                    @if($beasiswa->keterangan)
                    <tr>
                        <td class="text-muted fw-semibold">Keterangan</td>
                        <td>{{ $beasiswa->keterangan }}</td>
                    </tr>
                    @endif
                </table>
            </div>
            <div class="card-footer d-flex gap-2">
                @auth
                    @if(auth()->user()->isAdmin() || auth()->user()->role === 'user')
                    <a href="{{ route('beasiswa.edit', $beasiswa->id) }}" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil me-1"></i> Edit
                    </a>
                    @endif
                    @if(auth()->user()->isAdmin())
                    <form action="{{ route('beasiswa.destroy', $beasiswa->id) }}" method="POST" class="d-inline"
                          onsubmit="return confirm('Yakin hapus data ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="bi bi-trash me-1"></i> Hapus
                        </button>
                    </form>
                    @endif
                @endauth
            </div>
        </div>
    </div>

    <div class="col-md-4">
        {{-- SPK Fuzzy — Hasil Rekomendasi --}}
        <div class="card">
            <div class="card-header bg-success text-white">
                <i class="bi bi-cpu me-2"></i>SPK — Rekomendasi
            </div>
            <div class="card-body">
                @if($beasiswa->mahasiswa->fuzzyHasil)
                    <table class="table table-borderless mb-0">
                        <tr>
                            <td class="text-muted fw-semibold">Nilai Fuzzy</td>
                            <td class="fw-bold fs-5">{{ $beasiswa->mahasiswa->fuzzyHasil->nilai_fuzzy }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-semibold">Rekomendasi</td>
                            <td>
                                @php
                                    $fbadge = match($beasiswa->mahasiswa->fuzzyHasil->hasil_rekomendasi) {
                                        'Sangat Layak'     => 'bg-success',
                                        'Layak'            => 'bg-primary',
                                        'Dipertimbangkan'  => 'bg-warning text-dark',
                                        'Tidak Layak'      => 'bg-danger',
                                        default            => 'bg-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $fbadge }} fs-6">{{ $beasiswa->mahasiswa->fuzzyHasil->hasil_rekomendasi }}</span>
                            </td>
                        </tr>
                    </table>
                    <hr>
                    <small class="text-muted">
                        < 40 : Tidak Layak<br>
                        40–54 : Dipertimbangkan<br>
                        55–74 : Layak<br>
                        ≥ 75 : Sangat Layak
                    </small>
                @else
                    <div class="text-center text-muted py-3">
                        <i class="bi bi-cpu fs-2 d-block mb-2"></i>
                        <p class="mb-0">Belum ada hasil rekomendasi.</p>
                        @if(auth()->user()->isAdmin())
                        <form action="{{ route('beasiswa.hitung-rekomendasi') }}" method="POST" class="d-inline mt-2">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="bi bi-cpu me-1"></i> Hitung Sekarang
                            </button>
                        </form>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
