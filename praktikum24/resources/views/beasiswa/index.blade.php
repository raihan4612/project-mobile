@extends('layouts.app')

@section('title', 'Pengajuan Beasiswa')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-0 fw-bold"><i class="bi bi-file-text-fill text-primary me-2"></i>Pengajuan Beasiswa</h4>
        <small class="text-muted">Total: {{ $dtBeasiswa->total() }} pengajuan</small>
    </div>
    @auth
        @if(auth()->user()->isAdmin() || auth()->user()->role === 'user')
        <a href="{{ route('beasiswa.create') }}" class="btn btn-primary mt-2 mt-md-0">
            <i class="bi bi-plus-circle me-1"></i> Tambah Pengajuan
        </a>
        @endif
        @if(auth()->user()->isAdmin())
        <form action="{{ route('beasiswa.hitung-rekomendasi') }}" method="POST" class="d-inline mt-2 mt-md-0">
            @csrf
            <button type="submit" class="btn btn-success">
                <i class="bi bi-cpu me-1"></i> Hitung Rekomendasi
            </button>
        </form>
        @endif
    @else
        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm mt-2 mt-md-0">
            <i class="bi bi-box-arrow-in-right me-1"></i> Login untuk kelola data
        </a>
    @endauth
</div>

{{-- Stat Cards --}}
<div class="row g-1 mb-2">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm bg-primary text-white">
            <div class="card-body p-2 text-center">
                <i class="bi bi-file-text"></i>
                <h6 class="mb-0 fw-bold mt-1">{{ $totalDiajukan + $totalDisetujui + $totalDitolak }}</h6>
                <small style="font-size:11px">Total</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm bg-warning text-dark">
            <div class="card-body p-2 text-center">
                <i class="bi bi-clock"></i>
                <h6 class="mb-0 fw-bold mt-1">{{ $totalDiajukan }}</h6>
                <small style="font-size:11px">Diajukan</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm bg-success text-white">
            <div class="card-body p-2 text-center">
                <i class="bi bi-check-circle"></i>
                <h6 class="mb-0 fw-bold mt-1">{{ $totalDisetujui }}</h6>
                <small style="font-size:11px">Disetujui</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm bg-danger text-white">
            <div class="card-body p-2 text-center">
                <i class="bi bi-x-circle"></i>
                <h6 class="mb-0 fw-bold mt-1">{{ $totalDitolak }}</h6>
                <small style="font-size:11px">Ditolak</small>
            </div>
        </div>
    </div>
</div>

{{-- Charts --}}
<div class="row g-1 mb-2">
    <div class="col-md-2">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-1">
                <small class="fw-bold" style="font-size:10px"><i class="bi bi-pie-chart-fill text-primary me-1"></i> Status</small>
                <canvas id="statusChart" height="55"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-1">
        <div class="card border-0 shadow-sm h-100 bg-info text-white">
            <div class="card-body d-flex flex-column justify-content-center align-items-center text-center p-1">
                <small style="font-size:9px;line-height:1.1">Rata<br>Fuzzy</small>
                <span class="fw-bold" style="font-size:13px">{{ $rataFuzzy ? number_format($rataFuzzy, 2) : '-' }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-1">
                <small class="fw-bold" style="font-size:10px"><i class="bi bi-bar-chart-fill text-primary me-1"></i> Rekomendasi</small>
                <canvas id="rekomendasiChart" height="55"></canvas>
            </div>
        </div>
    </div>
</div>

<form method="GET" action="{{ route('beasiswa.index') }}" class="row g-2 mb-3">
    <div class="col-md-4">
        <div class="input-group input-group-sm">
            <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
            <input type="text" name="search" class="form-control" placeholder="Cari mahasiswa, beasiswa..." value="{{ request('search') }}">
        </div>
    </div>
    <div class="col-md-2">
        <select name="status" class="form-select form-select-sm">
            <option value="">Semua Status</option>
            <option value="Diajukan" {{ request('status') == 'Diajukan' ? 'selected' : '' }}>Diajukan</option>
            <option value="Disetujui" {{ request('status') == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
            <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
        </select>
    </div>
    <div class="col-md-2">
        <select name="rekomendasi" class="form-select form-select-sm">
            <option value="">Semua Rekomendasi</option>
            <option value="Tidak Layak" {{ request('rekomendasi') == 'Tidak Layak' ? 'selected' : '' }}>Tidak Layak</option>
            <option value="Dipertimbangkan" {{ request('rekomendasi') == 'Dipertimbangkan' ? 'selected' : '' }}>Dipertimbangkan</option>
            <option value="Layak" {{ request('rekomendasi') == 'Layak' ? 'selected' : '' }}>Layak</option>
            <option value="Sangat Layak" {{ request('rekomendasi') == 'Sangat Layak' ? 'selected' : '' }}>Sangat Layak</option>
        </select>
    </div>
    <div class="col-md-1">
        <button type="submit" class="btn btn-sm btn-primary w-100"><i class="bi bi-filter"></i></button>
    </div>
    <div class="col-md-1">
        <a href="{{ route('beasiswa.index') }}" class="btn btn-sm btn-outline-secondary w-100"><i class="bi bi-arrow-counterclockwise"></i></a>
    </div>
</form>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width:4%">#</th>
                        <th>Mahasiswa</th>
                        <th>Program Beasiswa</th>
                        <th>Status</th>
                        <th class="text-center">Nilai Fuzzy</th>
                        <th class="text-center">Rekomendasi</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($dtBeasiswa as $item)
                    <tr>
                        <td class="text-muted">{{ $dtBeasiswa->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="fw-semibold">{{ $item->mahasiswa->nama }}</div>
                            <small class="text-muted">{{ $item->mahasiswa->nim }}</small>
                        </td>
                        <td>{{ $item->programBeasiswa?->nama_beasiswa ?? '-' }}</td>
                        <td>
                            @php
                                $badge = match($item->status) {
                                    'Disetujui' => 'bg-success',
                                    'Ditolak'   => 'bg-danger',
                                    default     => 'bg-warning text-dark',
                                };
                            @endphp
                            <span class="badge {{ $badge }}">{{ $item->status }}</span>
                        </td>
                        <td class="text-center fw-semibold">
                            {{ $item->mahasiswa->fuzzyHasil?->nilai_fuzzy ?? '-' }}
                        </td>
                        <td class="text-center">
                            @if($item->mahasiswa->fuzzyHasil)
                                @php
                                    $fbadge = match($item->mahasiswa->fuzzyHasil->hasil_rekomendasi) {
                                        'Sangat Layak'     => 'bg-success',
                                        'Layak'            => 'bg-primary',
                                        'Dipertimbangkan'  => 'bg-warning text-dark',
                                        'Tidak Layak'      => 'bg-danger',
                                        default            => 'bg-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $fbadge }}">{{ $item->mahasiswa->fuzzyHasil->hasil_rekomendasi }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @auth
                            <a href="{{ route('beasiswa.show', $item->id) }}"
                               class="btn btn-sm btn-outline-info" title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>

                            @if(auth()->user()->isAdmin() || auth()->user()->role === 'user')
                            <a href="{{ route('beasiswa.edit', $item->id) }}"
                               class="btn btn-sm btn-outline-warning" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @endif

                            @if(auth()->user()->isAdmin())
                            <form action="{{ route('beasiswa.destroy', $item->id) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Yakin hapus data ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                            @endauth
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                            Belum ada pengajuan beasiswa
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    {{ $dtBeasiswa->links() }}
</div>
@endsection

@section('scripts')
<script>
var statusCtx = document.getElementById('statusChart').getContext('2d');
new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: @json($chartStatusLabels),
        datasets: [{
            data: @json($chartStatusData),
            backgroundColor: ['#f6c23e', '#1cc88a', '#e74a3b'],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom', labels: { font: { size: 10 } } }
        }
    }
});

var rekomCtx = document.getElementById('rekomendasiChart').getContext('2d');
new Chart(rekomCtx, {
    type: 'bar',
    data: {
        labels: @json($chartRekomendasiLabels),
        datasets: [{
            label: 'Jumlah',
            data: @json($chartRekomendasiData),
            backgroundColor: ['#e74a3b', '#f6c23e', '#4e73df', '#1cc88a'],
            borderRadius: 4
        }]
    },
    options: {
        responsive: true,
        indexAxis: 'y',
        scales: {
            x: { beginAtZero: true, ticks: { stepSize: 1 } }
        },
        plugins: {
            legend: { display: false }
        }
    }
});
</script>
@endsection