@extends('layouts.app')

@section('title', 'Detail Prestasi')

@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('prestasi.index') }}" class="btn btn-outline-secondary btn-sm me-3">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
    <h4 class="mb-0 fw-bold"><i class="bi bi-trophy-fill text-primary me-2"></i>Detail Prestasi</h4>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <i class="bi bi-info-circle me-2"></i>Informasi Prestasi
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr>
                        <td class="text-muted fw-semibold" style="width:35%">Mahasiswa</td>
                        <td>{{ $prestasi->mahasiswa->nama }} <small class="text-muted">({{ $prestasi->mahasiswa->nim }})</small></td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">Nama Lomba</td>
                        <td class="fw-semibold">{{ $prestasi->nama_lomba }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">Penyelenggara</td>
                        <td>{{ $prestasi->penyelenggara }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">Tanggal</td>
                        <td>{{ $prestasi->tanggal->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">Jenis Prestasi</td>
                        <td><span class="badge bg-secondary">{{ $prestasi->jenis->nama_jenis }}</span></td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">Tingkat</td>
                        <td><span class="badge bg-info text-dark">{{ $prestasi->tingkat->nama_tingkat }}</span></td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">Juara / Peringkat</td>
                        <td>{{ $prestasi->juara ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">Sertifikat</td>
                        <td>
                            @if($prestasi->sertifikat)
                                <a href="{{ Storage::url($prestasi->sertifikat) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-file-earmark me-1"></i>Lihat Sertifikat
                                </a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">Status</td>
                        <td>
                            @php
                                $badge = match($prestasi->status_verifikasi) {
                                    'Disetujui' => 'bg-success',
                                    'Ditolak'   => 'bg-danger',
                                    default     => 'bg-warning text-dark',
                                };
                            @endphp
                            <span class="badge {{ $badge }}">{{ $prestasi->status_verifikasi }}</span>
                        </td>
                    </tr>
                </table>
            </div>

            {{-- Footer aksi: edit (user & admin), hapus (admin saja) --}}
            <div class="card-footer d-flex gap-2">
                @if(auth()->user()->isAdmin() || auth()->user()->role === 'user')
                <a href="{{ route('prestasi.edit', $prestasi->id) }}" class="btn btn-warning btn-sm">
                    <i class="bi bi-pencil me-1"></i> Edit
                </a>
                @endif

                @if(auth()->user()->isAdmin())
                <form action="{{ route('prestasi.destroy', $prestasi->id) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('Yakin hapus data prestasi ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="bi bi-trash me-1"></i> Hapus
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        {{-- Verifikasi: hanya admin --}}
        @if(auth()->user()->isAdmin() && $prestasi->status_verifikasi === 'Pending')
        <div class="card mb-3">
            <div class="card-header bg-warning text-dark">
                <i class="bi bi-shield-check me-2"></i>Verifikasi Prestasi
            </div>
            <div class="card-body">
                <form action="{{ route('prestasi.verifikasi', $prestasi->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Keputusan</label>
                        <select name="status_verifikasi" class="form-select" required>
                            <option value="">-- Pilih --</option>
                            <option value="Disetujui">✅ Setujui</option>
                            <option value="Ditolak">❌ Tolak</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Catatan (opsional)</label>
                        <textarea name="catatan" class="form-control" rows="3" placeholder="Catatan verifikasi..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-warning w-100">
                        <i class="bi bi-check2-circle me-1"></i> Proses Verifikasi
                    </button>
                </form>
            </div>
        </div>
        @endif

        {{-- Riwayat Verifikasi --}}
        @if($prestasi->verifikasi)
        <div class="card">
            <div class="card-header bg-light">
                <i class="bi bi-clock-history me-2"></i>Riwayat Verifikasi
            </div>
            <div class="card-body">
                <p class="mb-1"><strong>Admin:</strong> {{ $prestasi->verifikasi->admin->nama }}</p>
                <p class="mb-1"><strong>Tanggal:</strong> {{ $prestasi->verifikasi->tanggal_verifikasi->format('d/m/Y') }}</p>
                @if($prestasi->verifikasi->catatan)
                <p class="mb-0"><strong>Catatan:</strong><br>
                    <small class="text-muted">{{ $prestasi->verifikasi->catatan }}</small>
                </p>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
