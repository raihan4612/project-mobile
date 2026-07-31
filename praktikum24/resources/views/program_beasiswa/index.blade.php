@extends('layouts.app')

@section('title', 'Program Beasiswa')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-0 fw-bold"><i class="bi bi-award-fill text-primary me-2"></i>Program Beasiswa</h4>
        <small class="text-muted">Total: {{ $dtProgram->total() }} program</small>
    </div>
    @auth
        @if(auth()->user()->isAdmin() || auth()->user()->role === 'user')
        <a href="{{ route('program-beasiswa.create') }}" class="btn btn-primary mt-2 mt-md-0">
            <i class="bi bi-plus-circle me-1"></i> Tambah Program
        </a>
        @endif
    @else
        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm mt-2 mt-md-0">
            <i class="bi bi-box-arrow-in-right me-1"></i> Login untuk kelola data
        </a>
    @endauth
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width:4%">#</th>
                        <th>Nama Beasiswa</th>
                        <th>Penyelenggara</th>
                        <th>Tahun</th>
                        <th>Dana</th>
                        <th class="text-center">Pengaju</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($dtProgram as $item)
                    <tr>
                        <td class="text-muted">{{ $dtProgram->firstItem() + $loop->index }}</td>
                        <td class="fw-semibold">{{ $item->nama_beasiswa }}</td>
                        <td>{{ $item->penyelenggara }}</td>
                        <td>{{ $item->tahun_akademik }}</td>
                        <td class="fw-semibold">Rp {{ number_format($item->jumlah_dana, 0, ',', '.') }}</td>
                        <td class="text-center">
                            <span class="badge bg-secondary">{{ $item->beasiswa_count }}</span>
                        </td>
                        <td class="text-center">
                            @auth
                            @if(auth()->user()->isAdmin() || auth()->user()->role === 'user')
                            <a href="{{ route('program-beasiswa.edit', $item->id) }}"
                               class="btn btn-sm btn-outline-warning" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @endif
                            @if(auth()->user()->isAdmin())
                            <form action="{{ route('program-beasiswa.destroy', $item->id) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Yakin hapus program {{ $item->nama_beasiswa }}?')">
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
                            <i class="bi bi-award fs-3 d-block mb-2"></i>
                            Belum ada program beasiswa
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    {{ $dtProgram->links() }}
</div>
@endsection
