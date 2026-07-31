@extends('layouts.app')

@section('title', 'Detail Mahasiswa')

@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('data-mahasiswa') }}" class="btn btn-outline-secondary btn-sm me-3">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
    <h4 class="mb-0 fw-bold"><i class="bi bi-person-circle text-primary me-2"></i>Detail Mahasiswa</h4>
</div>

<div class="card">
    <div class="card-header bg-primary text-white">
        <i class="bi bi-person-badge me-2"></i>{{ $dtMhs->nama }}
    </div>
    <div class="card-body">
        <div class="row g-3">

            {{-- Identitas --}}
            <div class="col-12"><h6 class="text-muted border-bottom pb-1">Identitas Pribadi</h6></div>
            <div class="col-md-3 fw-semibold">NIM</div>
            <div class="col-md-9">{{ $dtMhs->nim }}</div>

            <div class="col-md-3 fw-semibold">Nama Lengkap</div>
            <div class="col-md-9">{{ $dtMhs->nama }}</div>

            <div class="col-md-3 fw-semibold">Jenis Kelamin</div>
            <div class="col-md-9">{{ $dtMhs->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</div>

            <div class="col-md-3 fw-semibold">Tempat, Tanggal Lahir</div>
            <div class="col-md-9">{{ $dtMhs->tempat_lahir }}, {{ $dtMhs->tanggal_lahir }}</div>

            {{-- Kontak --}}
            <div class="col-12 mt-2"><h6 class="text-muted border-bottom pb-1">Kontak & Alamat</h6></div>
            <div class="col-md-3 fw-semibold">No. HP</div>
            <div class="col-md-9">{{ $dtMhs->no_hp }}</div>

            <div class="col-md-3 fw-semibold">Email</div>
            <div class="col-md-9">{{ $dtMhs->email }}</div>

            <div class="col-md-3 fw-semibold">Alamat</div>
            <div class="col-md-9">{{ $dtMhs->alamat }}</div>

            <div class="col-md-3 fw-semibold">Kota</div>
            <div class="col-md-9">{{ $dtMhs->kota }}</div>

            <div class="col-md-3 fw-semibold">Provinsi</div>
            <div class="col-md-9">{{ $dtMhs->provinsi }}</div>

            <div class="col-md-3 fw-semibold">Kode Pos</div>
            <div class="col-md-9">{{ $dtMhs->kode_pos ?? '-' }}</div>

            {{-- Akademik --}}
            <div class="col-12 mt-2"><h6 class="text-muted border-bottom pb-1">Data Akademik</h6></div>
            <div class="col-md-3 fw-semibold">Program Studi</div>
            <div class="col-md-9">{{ $dtMhs->prodi }}</div>

            <div class="col-md-3 fw-semibold">Fakultas</div>
            <div class="col-md-9">{{ $dtMhs->fakultas }}</div>

            <div class="col-md-3 fw-semibold">Semester</div>
            <div class="col-md-9">{{ $dtMhs->semester }}</div>

            <div class="col-md-3 fw-semibold">IPK</div>
            <div class="col-md-9">{{ $dtMhs->ipk ? number_format($dtMhs->ipk, 2) : '-' }}</div>

            <div class="col-md-3 fw-semibold">Tahun Masuk</div>
            <div class="col-md-9">{{ $dtMhs->tahun_masuk }}</div>

            <div class="col-md-3 fw-semibold">Status</div>
            <div class="col-md-9">
                @php
                    $badgeClass = match($dtMhs->status) {
                        'Aktif'   => 'bg-success',
                        'Cuti'    => 'bg-warning text-dark',
                        'Lulus'   => 'bg-info text-dark',
                        'Dropout' => 'bg-danger',
                        default   => 'bg-secondary',
                    };
                @endphp
                <span class="badge {{ $badgeClass }}">{{ $dtMhs->status }}</span>
            </div>

        </div>
    </div>

    {{-- Footer aksi: edit (user & admin), hapus (admin saja) --}}
    <div class="card-footer d-flex gap-2">
        @if(auth()->user()->isAdmin() || auth()->user()->role === 'user')
        <a href="{{ route('edit-mahasiswa', $dtMhs->id) }}" class="btn btn-warning btn-sm">
            <i class="bi bi-pencil me-1"></i> Edit
        </a>
        @endif

        @if(auth()->user()->isAdmin())
        <form action="{{ route('hapus-mahasiswa', $dtMhs->id) }}" method="POST" class="d-inline"
              onsubmit="return confirm('Yakin hapus data ini?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm">
                <i class="bi bi-trash me-1"></i> Hapus
            </button>
        </form>
        @endif
    </div>
</div>
@endsection
