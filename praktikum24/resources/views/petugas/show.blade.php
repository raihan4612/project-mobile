@extends('layouts.app')

@section('title', 'Detail Petugas')

@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('petugas.index') }}"
       class="btn btn-outline-secondary btn-sm me-3">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>

    <h4 class="mb-0 fw-bold">
        <i class="bi bi-person-circle text-primary me-2"></i>
        Detail Petugas
    </h4>
</div>

<div class="card">

    <div class="card-header bg-primary text-white">
        <i class="bi bi-person-badge me-2"></i>
        {{ $petugas->nama }}
    </div>

    <div class="card-body">
        <div class="row g-3">

            <div class="col-md-3 fw-semibold">Kode Petugas</div>
            <div class="col-md-9">{{ $petugas->kode_petugas }}</div>

            <div class="col-md-3 fw-semibold">Nama</div>
            <div class="col-md-9">{{ $petugas->nama }}</div>

            <div class="col-md-3 fw-semibold">Email</div>
            <div class="col-md-9">{{ $petugas->email }}</div>

            <div class="col-md-3 fw-semibold">No HP</div>
            <div class="col-md-9">{{ $petugas->no_hp }}</div>

            <div class="col-md-3 fw-semibold">Jabatan</div>
            <div class="col-md-9">{{ $petugas->jabatan }}</div>

            <div class="col-md-3 fw-semibold">Status</div>
            <div class="col-md-9">
                <span class="badge {{ $petugas->status == 'Aktif' ? 'bg-success' : 'bg-secondary' }}">
                    {{ $petugas->status }}
                </span>
            </div>

            <div class="col-md-3 fw-semibold">Total Peminjaman</div>
            <div class="col-md-9">
                {{ $petugas->peminjaman_count }}
            </div>

        </div>
    </div>

    <div class="card-footer d-flex gap-2">

        <a href="{{ route('petugas.edit', $petugas->id) }}"
           class="btn btn-warning btn-sm">
            <i class="bi bi-pencil me-1"></i> Edit
        </a>

        <form action="{{ route('petugas.destroy', $petugas->id) }}"
              method="POST"
              class="d-inline"
              onsubmit="return confirm('Yakin hapus data ini?')">

            @csrf
            @method('DELETE')

            <button type="submit"
                    class="btn btn-danger btn-sm">
                <i class="bi bi-trash me-1"></i> Hapus
            </button>
        </form>

    </div>
</div>
@endsection