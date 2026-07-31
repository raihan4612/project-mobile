@extends('layouts.app')

@section('title', 'Prestasi Jenis: ' . $jenis->nama_jenis)

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-0 fw-bold">
            <i class="bi bi-tag-fill text-primary me-2"></i>Prestasi Jenis:
            <span class="text-primary">{{ $jenis->nama_jenis }}</span>
        </h4>
        <small class="text-muted">Total: {{ $dtPrestasi->total() }} prestasi</small>
    </div>
    <a href="{{ route('jenis-prestasi.index') }}" class="btn btn-outline-secondary mt-2 mt-md-0">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width:4%">#</th>
                        <th>Mahasiswa</th>
                        <th>Lomba</th>
                        <th>Tingkat</th>
                        <th>Juara</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($dtPrestasi as $item)
                    <tr>
                        <td class="text-muted">{{ $dtPrestasi->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="fw-semibold">{{ $item->mahasiswa?->nama ?? '-' }}</div>
                            <small class="text-muted">{{ $item->mahasiswa?->nim ?? '' }}</small>
                        </td>
                        <td>{{ Str::limit($item->nama_lomba, 35) }}</td>
                        <td><span class="badge bg-info text-dark">{{ $item->tingkat?->nama_tingkat ?? '-' }}</span></td>
                        <td>{{ $item->juara ?? '-' }}</td>
                        <td>{{ $item->tanggal?->format('d/m/Y') ?? '-' }}</td>
                        <td>
                            @php
                                $badge = match($item->status_verifikasi) {
                                    'Disetujui' => 'bg-success',
                                    'Ditolak'   => 'bg-danger',
                                    default     => 'bg-warning text-dark',
                                };
                            @endphp
                            <span class="badge {{ $badge }}">{{ $item->status_verifikasi }}</span>
                        </td>
                        <td class="text-center">
                            @auth
                            <a href="{{ route('prestasi.show', $item->id) }}"
                               class="btn btn-sm btn-outline-info" title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                            @if(auth()->user()->isAdmin() || auth()->user()->role === 'user')
                            <a href="{{ route('prestasi.edit', $item->id) }}"
                               class="btn btn-sm btn-outline-warning" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @endif
                            @if(auth()->user()->isAdmin())
                            <form action="{{ route('prestasi.destroy', $item->id) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Yakin hapus prestasi {{ $item->nama_lomba }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                            @else
                            <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-box-arrow-in-right"></i>
                            </a>
                            @endauth
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="bi bi-tag fs-3 d-block mb-2"></i>
                            Belum ada prestasi untuk jenis ini
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    {{ $dtPrestasi->links() }}
</div>
@endsection