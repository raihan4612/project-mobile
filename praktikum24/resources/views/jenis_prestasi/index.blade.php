@extends('layouts.app')

@section('title', 'Jenis Prestasi')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-0 fw-bold"><i class="bi bi-tag-fill text-primary me-2"></i>Jenis Prestasi</h4>
        <small class="text-muted">Total: {{ $dtJenis->total() }} jenis</small>
    </div>
    @auth
        @if(auth()->user()->isAdmin() || auth()->user()->role === 'user')
        <a href="{{ route('jenis-prestasi.create') }}" class="btn btn-primary mt-2 mt-md-0">
            <i class="bi bi-plus-circle me-1"></i> Tambah Jenis
        </a>
        @endif
    @else
        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm mt-2 mt-md-0">
            <i class="bi bi-box-arrow-in-right me-1"></i> Login untuk kelola data
        </a>
    @endauth
</div>

<div class="row">
    <div class="col-lg-5 mb-3">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="fw-bold mb-3"><i class="bi bi-pie-chart-fill text-primary me-1"></i> Grafik Jenis Prestasi</h6>
                <canvas id="jenisChart" height="200"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th style="width:4%">#</th>
                                <th>Nama Jenis</th>
                                <th class="text-center">Jumlah Prestasi</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($dtJenis as $item)
                            <tr>
                                <td class="text-muted">{{ $dtJenis->firstItem() + $loop->index }}</td>
                                <td class="fw-semibold">{{ $item->nama_jenis }}</td>
                                <td class="text-center">
                                    <a href="{{ route('jenis-prestasi.detail', $item->id) }}"
                                       class="badge bg-secondary text-decoration-none">
                                        {{ $item->prestasi_count }}
                                    </a>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('jenis-prestasi.detail', $item->id) }}"
                               class="btn btn-sm btn-outline-primary" title="Lihat Prestasi">
                                <i class="bi bi-eye"></i>
                            </a>
                                    @auth
                                    <a href="{{ route('jenis-prestasi.edit', $item->id) }}"
                                       class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    @if(auth()->user()->isAdmin())
                                    <form action="{{ route('jenis-prestasi.destroy', $item->id) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Yakin hapus jenis {{ $item->nama_jenis }}?')">
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
                                <td colspan="4" class="text-center text-muted py-4">
                                    <i class="bi bi-tag fs-3 d-block mb-2"></i>
                                    Belum ada jenis prestasi
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mt-3">
    {{ $dtJenis->links() }}
</div>
@endsection

@section('scripts')
<script>
const ctx = document.getElementById('jenisChart').getContext('2d');
new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: @json($chartLabels),
        datasets: [{
            data: @json($chartData),
            backgroundColor: ['#4e73df', '#1cc88a', '#f6c23e', '#e74a3b'],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom', labels: { font: { size: 11 } } }
        }
    }
});
</script>
@endsection
