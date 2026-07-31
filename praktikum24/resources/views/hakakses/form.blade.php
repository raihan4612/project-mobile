{{-- Gunakan file ini untuk KEDUA form create & edit --}}
{{-- create: action=route('simpan-hak-akses'), method=POST --}}
{{-- edit  : action=route('update-hak-akses', $role->id), method=POST + @method('PUT') --}}

@extends('layouts.app')

@section('title', isset($role) ? 'Edit Hak Akses' : 'Tambah Hak Akses')

@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('hak-akses') }}" class="btn btn-outline-secondary btn-sm me-3">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
    <h4 class="mb-0 fw-bold">
        <i class="bi bi-shield-plus text-primary me-2"></i>
        {{ isset($role) ? 'Edit Hak Akses' : 'Tambah Hak Akses' }}
    </h4>
</div>

<div class="card" style="max-width: 680px;">
    <div class="card-header bg-primary text-white">
        <i class="bi bi-key-fill me-2"></i>Form Hak Akses
    </div>
    <div class="card-body">
        <form action="{{ isset($role) ? route('update-hak-akses', $role->id) : route('simpan-hak-akses') }}"
              method="POST">
            @csrf
            @if(isset($role)) @method('PUT') @endif

            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Role <span class="text-danger">*</span></label>
                <input type="text" name="nama_role"
                       class="form-control @error('nama_role') is-invalid @enderror"
                       value="{{ old('nama_role', $role->nama_role ?? '') }}"
                       placeholder="Contoh: Admin, Mahasiswa, Dosen">
                @error('nama_role') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Deskripsi</label>
                <textarea name="deskripsi" rows="2"
                          class="form-control @error('deskripsi') is-invalid @enderror"
                          placeholder="Penjelasan singkat tentang role ini">{{ old('deskripsi', $role->deskripsi ?? '') }}</textarea>
                @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <label class="form-label fw-semibold">Izin / Permission</label>
            <div class="row g-3 mb-3">
                @php
                    $perms = [
                        'can_create' => ['label'=>'Create',  'icon'=>'bi-plus-circle',   'color'=>'success'],
                        'can_read'   => ['label'=>'Read',    'icon'=>'bi-eye',            'color'=>'info'],
                        'can_update' => ['label'=>'Update',  'icon'=>'bi-pencil',         'color'=>'warning'],
                        'can_delete' => ['label'=>'Delete',  'icon'=>'bi-trash',          'color'=>'danger'],
                        'can_export' => ['label'=>'Export',  'icon'=>'bi-download',       'color'=>'secondary'],
                    ];
                @endphp
                @foreach($perms as $key => $p)
                <div class="col-6 col-md-4">
                    <div class="form-check form-switch border rounded p-3 ps-5">
                        <input class="form-check-input" type="checkbox"
                               name="{{ $key }}" id="{{ $key }}"
                               {{ old($key, isset($role) && $role->$key ? 'on' : '') == 'on' ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="{{ $key }}">
                            <i class="bi {{ $p['icon'] }} text-{{ $p['color'] }} me-1"></i>
                            {{ $p['label'] }}
                        </label>
                    </div>
                </div>
                @endforeach
                <div class="col-6 col-md-4">
                    <div class="form-check form-switch border rounded p-3 ps-5">
                        <input class="form-check-input" type="checkbox"
                               name="is_active" id="is_active"
                               {{ old('is_active', isset($role) && $role->is_active ? 'on' : '') == 'on' ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="is_active">
                            <i class="bi bi-toggle-on text-primary me-1"></i>Aktif
                        </label>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-save me-1"></i> Simpan
                </button>
                <a href="{{ route('hak-akses') }}" class="btn btn-outline-secondary px-4">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
