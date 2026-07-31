@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-16">

        {{-- Profile Card --}}
        <div class="card border-0 shadow-sm overflow-hidden mb-4">
            {{-- Cover --}}
            <div class="position-relative" style="height:180px;background:linear-gradient(135deg,#1a3c6e 0%,#2563a8 50%,#4f8cd4 100%);">
                <div class="position-absolute bottom-0 start-0 w-100 text-white p-3 d-flex align-items-end" style="background:linear-gradient(transparent,rgba(0,0,0,.4));">
                    <div style="width:90px;height:90px;" class="rounded-circle border border-3 border-white overflow-hidden me-3 bg-white d-flex align-items-center justify-content-center flex-shrink-0">
                        @if ($mahasiswa && $mahasiswa->foto)
                            <img src="{{ asset('storage/' . $mahasiswa->foto) }}" alt="Foto" class="w-100 h-100 object-fit-cover">
                        @else
                            <span class="fw-bold text-primary" style="font-size:2rem">{{ substr($user->nama, 0, 1) }}</span>
                        @endif
                    </div>
                    <div class="pb-1">
                        <h5 class="mb-0 fw-bold text-white">{{ $user->nama }}</h5>
                        <span class="badge bg-light text-dark mt-1">{{ ucfirst($user->role) }}</span>
                    </div>
                </div>
            </div>

            {{-- Body --}}
            <div class="card-body pt-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h6 class="fw-bold mb-1">{{ $user->nama }}</h6>
                        <small class="text-muted"><i class="bi bi-envelope me-1"></i>{{ $user->email }}</small>
                    </div>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                        <i class="bi bi-pencil-square me-1"></i> Edit Profil
                    </button>
                </div>

                {{-- Stats --}}
                @if ($mahasiswa)
                <div class="row g-2 mb-3">
                    <div class="col-4">
                        <div class="bg-light rounded p-2 text-center">
                            <small class="text-muted d-block">Semester</small>
                            <span class="fw-bold">{{ $mahasiswa->semester }}</span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="bg-light rounded p-2 text-center">
                            <small class="text-muted d-block">IPK</small>
                            <span class="fw-bold">{{ number_format($mahasiswa->ipk, 2) }}</span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="bg-light rounded p-2 text-center">
                            <small class="text-muted d-block">Status</small>
                            <span class="badge bg-success">{{ $mahasiswa->status }}</span>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Detail Info --}}
                <ul class="list-group list-group-flush">
                    <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-person-badge text-primary me-2"></i>Role</span>
                        <span class="fw-semibold">{{ ucfirst($user->role) }}</span>
                    </li>
                    @if ($mahasiswa)
                    <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-credit-card text-primary me-2"></i>NIM</span>
                        <span class="fw-semibold">{{ $mahasiswa->nim }}</span>
                    </li>
                    <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-book text-primary me-2"></i>Program Studi</span>
                        <span class="fw-semibold">{{ $mahasiswa->prodi }}</span>
                    </li>
                    <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-building text-primary me-2"></i>Fakultas</span>
                        <span class="fw-semibold">{{ $mahasiswa->fakultas }}</span>
                    </li>
                    <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-calendar text-primary me-2"></i>Tahun Masuk</span>
                        <span class="fw-semibold">{{ $mahasiswa->tahun_masuk }}</span>
                    </li>
                    @if ($mahasiswa->alamat)
                    <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-geo-alt text-primary me-2"></i>Alamat</span>
                        <span class="fw-semibold text-end" style="max-width:60%">{{ $mahasiswa->alamat }}, {{ $mahasiswa->kota }}</span>
                    </li>
                    @endif
                    <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-phone text-primary me-2"></i>No. HP</span>
                        <span class="fw-semibold">{{ $mahasiswa->no_hp ?? '-' }}</span>
                    </li>
                    @endif
                </ul>
            </div>
        </div>

        {{-- Ganti Password --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-bold">
                <i class="bi bi-key text-warning me-2"></i>Ganti Password
            </div>
            <div class="card-body">
                <form action="{{ route('profile.password') }}" method="POST">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-4">
                            <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" placeholder="Password saat ini" required>
                            @error('current_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3">
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password baru" required>
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3">
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Konfirmasi password" required>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-warning w-100"><i class="bi bi-shield-check"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

{{-- Modal Edit Profil --}}
<div class="modal fade" id="editProfileModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf @method('PUT')
                <div class="modal-header">
                    <h6 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i>Edit Profil</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $user->nama) }}">
                        @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}">
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    @if ($mahasiswa)
                    <div class="mb-3">
                        <label class="form-label fw-semibold">No. HP</label>
                        <input type="text" name="no_hp" class="form-control" value="{{ $mahasiswa->no_hp }}" placeholder="Nomor handphone">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Alamat</label>
                        <textarea name="alamat" class="form-control" rows="2" placeholder="Alamat lengkap">{{ $mahasiswa->alamat }}</textarea>
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection