<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMAK — Sistem Informasi Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --navy: #1a3c6e;
            --blue: #2563a8;
            --accent: #f0a500;
            --bg: #f0f2f5;
            --card-shadow: 0 1px 3px rgba(0,0,0,.08);
            --card-hover-shadow: 0 4px 12px rgba(37,99,168,.12);
        }
        * { box-sizing: border-box; }
        body { margin:0; font-family:'Segoe UI',system-ui,sans-serif; background:var(--bg); color:#1e2d42; font-size:.9rem; }

        .topbar {
            position:sticky; top:0; z-index:100;
            background:var(--navy); padding:.55rem 1.5rem;
            display:flex; align-items:center; justify-content:space-between;
            box-shadow:0 2px 8px rgba(0,0,0,.15);
        }
        .topbar-brand {
            display:flex; align-items:center; gap:.5rem;
            color:#fff; font-weight:700; font-size:.95rem; text-decoration:none;
        }
        .topbar-brand i { font-size:1.2rem; color:var(--accent); }
        .topbar-brand small { font-weight:400; font-size:.65rem; color:rgba(255,255,255,.5); margin-left:.25rem; }
        .topbar-right { display:flex; align-items:center; gap:.5rem; flex-wrap:wrap; }
        .topbar-user { color:rgba(255,255,255,.8); font-size:.78rem; white-space:nowrap; }
        .btn-topbar {
            background:var(--accent); color:var(--navy); border:none;
            border-radius:6px; padding:.3rem .85rem; font-weight:600;
            font-size:.78rem; text-decoration:none; display:inline-flex; align-items:center; gap:.35rem;
            transition:background .15s;
        }
        .btn-topbar:hover { background:#ffc02e; color:var(--navy); }
        .btn-topbar-outline {
            background:rgba(255,255,255,.1); color:#fff;
            border:1px solid rgba(255,255,255,.2); border-radius:6px;
            padding:.3rem .8rem; font-size:.78rem; cursor:pointer;
            display:inline-flex; align-items:center; gap:.35rem;
        }
        .btn-topbar-outline:hover { background:rgba(255,255,255,.2); }

        .hero {
            background:linear-gradient(135deg,var(--navy),var(--blue));
            color:#fff; text-align:center; padding:2rem 1rem 1.5rem; position:relative; overflow:hidden;
        }
        .hero-icon { font-size:2rem; color:var(--accent); display:block; margin-bottom:.4rem; animation:float 3s ease-in-out infinite; }
        @keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-5px)} }
        .hero h1 { font-size:clamp(1.2rem,2.5vw,1.7rem); font-weight:800; margin:0 0 .2rem; }
        .hero h1 span { color:var(--accent); }
        .hero p { font-size:.8rem; color:rgba(255,255,255,.65); max-width:450px; margin:0 auto; line-height:1.5; }

        .section-label {
            font-size:.7rem; font-weight:700; text-transform:uppercase;
            letter-spacing:.06em; color:#8a9db0; margin-bottom:.6rem;
        }
        .stat-box {
            border-radius:10px; padding:.85rem .75rem; text-align:center;
            box-shadow:var(--card-shadow); height:100%;
        }
        .stat-box .num { font-size:1.4rem; font-weight:800; line-height:1.2; }
        .stat-box .lbl { font-size:.7rem; opacity:.85; margin-top:.15rem; }

        .menu-card {
            display:block; background:#fff; border-radius:10px; padding:1rem .75rem;
            text-align:center; text-decoration:none; color:var(--navy);
            box-shadow:var(--card-shadow); border:1.5px solid transparent;
            transition:transform .18s, box-shadow .18s;
        }
        .menu-card:hover {
            transform:translateY(-3px); box-shadow:var(--card-hover-shadow);
            border-color:var(--blue); color:var(--navy);
        }
        .menu-card .mc-icon {
            width:40px; height:40px; border-radius:10px;
            display:flex; align-items:center; justify-content:center;
            font-size:1.1rem; margin:0 auto .5rem;
        }
        .menu-card .mc-label { font-weight:700; font-size:.82rem; }
        .menu-card .mc-desc { font-size:.68rem; color:#8a9db0; margin-top:.1rem; }

        footer { text-align:center; padding:.9rem; font-size:.72rem; color:#aab8c8; border-top:1px solid #e4eaf2; }
    </style>
</head>
<body>

{{-- Topbar --}}
<nav class="topbar">
    <a href="{{ url('/') }}" class="topbar-brand">
        <i class="bi bi-mortarboard-fill"></i>
        SIMAK <small>Sistem Informasi Mahasiswa</small>
    </a>
    <div class="topbar-right">
        @auth
            <span class="topbar-user"><i class="bi bi-person-circle me-1"></i>{{ auth()->user()->nama }}</span>
            <a href="{{ route('profile.index') }}" class="btn-topbar"><i class="bi bi-person-gear"></i> Profil</a>
            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="btn-topbar-outline"><i class="bi bi-box-arrow-left"></i></button>
            </form>
        @else
            <a href="{{ route('login') }}" class="btn-topbar"><i class="bi bi-box-arrow-in-right"></i> Login</a>
        @endauth
    </div>
</nav>

{{-- Hero --}}
<section class="hero">
    <i class="bi bi-mortarboard-fill hero-icon"></i>
    <h1>Selamat Datang di <span>SIMAK</span></h1>
    <p>Kelola data mahasiswa, buku perpustakaan, peminjaman, prestasi, dan beasiswa dalam satu tempat.</p>
</section>

@auth
<div class="container-fluid px-3 py-3" style="max-width:1000px;margin:0 auto">

    {{-- Stat Cards --}}
    <div class="row g-2 mb-3">
        <div class="col-6 col-md-3">
            <div class="stat-box" style="background:linear-gradient(135deg,#e8f0fb,#d4e4f7);color:var(--blue);">
                <div class="num">{{ $totalMahasiswa }}</div><div class="lbl"><i class="bi bi-people-fill me-1"></i>Mahasiswa</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-box" style="background:linear-gradient(135deg,#fff4e0,#ffe8c8);color:#c07100;">
                <div class="num">{{ $totalBuku }}</div><div class="lbl"><i class="bi bi-book-fill me-1"></i>Buku</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-box" style="background:linear-gradient(135deg,#e6f7ef,#cceae0);color:#1a8a52;">
                <div class="num">{{ $totalPeminjaman }}</div><div class="lbl"><i class="bi bi-journal-bookmark-fill me-1"></i>Peminjaman</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-box" style="background:linear-gradient(135deg,#fdf0fb,#f5dff2);color:#8c1fa0;">
                <div class="num">{{ $totalPrestasi }}</div><div class="lbl"><i class="bi bi-trophy-fill me-1"></i>Prestasi</div>
            </div>
        </div>
    </div>

    {{-- Menu Cards --}}
    <div class="mb-3">
        <div class="section-label">Jelajahi Menu</div>
        <div class="row g-2">
            @php
                $role = auth()->user()->role;
                $all = [
                    'data-mahasiswa'     => ['Mahasiswa','bi-people-fill','#e8f0fb','#2563a8','Data mahasiswa'],
                    'buku.index'         => ['Buku','bi-book-fill','#fff4e0','#c07100','Koleksi buku'],
                    'peminjaman.index'   => ['Peminjaman','bi-journal-bookmark-fill','#e6f7ef','#1a8a52','Riwayat peminjaman'],
                    'prestasi.index'     => ['Prestasi','bi-trophy-fill','#fdf0fb','#8c1fa0','Pencapaian mahasiswa'],
                    'beasiswa.index'     => ['Beasiswa','bi-file-text-fill','#e8f0fb','#2563a8','Pengajuan beasiswa'],
                    'program-beasiswa.index' => ['Program Beasiswa','bi-award-fill','#fff4e0','#c07100','Program tersedia'],
                    'jenis-prestasi.index'   => ['Jenis Prestasi','bi-tags-fill','#e6f7ef','#1a8a52','Kategori prestasi'],
                    'tingkat-prestasi.index' => ['Tingkat Prestasi','bi-bar-chart-fill','#fdf0fb','#8c1fa0','Tingkat prestasi'],
                ];
                $allowed = match($role) {
                    'admin'   => array_keys($all),
                    'petugas' => ['peminjaman.index','buku.index','data-mahasiswa','prestasi.index'],
                    'user'    => ['prestasi.index','beasiswa.index','program-beasiswa.index','peminjaman.index'],
                    default   => [],
                };
            @endphp
            @foreach($allowed as $r)
                @php $m = $all[$r]; @endphp
                <div class="col-6 col-md-3">
                    <a href="{{ route($r) }}" class="menu-card">
                        <div class="mc-icon" style="background:{{ $m[2] }};color:{{ $m[3] }}"><i class="bi {{ $m[1] }}"></i></div>
                        <div class="mc-label">{{ $m[0] }}</div>
                        <div class="mc-desc">{{ $m[4] }}</div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>

</div>
@else
{{-- Guest --}}
<div style="padding:2rem 1rem;max-width:500px;margin:0 auto">
    <div class="section-label text-center">Jelajahi Menu</div>
    <div class="row g-2 mb-3">
        <div class="col-6"><a href="{{ route('data-mahasiswa') }}" class="menu-card"><div class="mc-icon" style="background:#e8f0fb;color:#2563a8"><i class="bi bi-people-fill"></i></div><div class="mc-label">Mahasiswa</div><div class="mc-desc">Lihat data mahasiswa</div></a></div>
        <div class="col-6"><a href="{{ route('buku.index') }}" class="menu-card"><div class="mc-icon" style="background:#fff4e0;color:#c07100"><i class="bi bi-book-fill"></i></div><div class="mc-label">Buku</div><div class="mc-desc">Koleksi buku</div></a></div>
        <div class="col-6"><a href="{{ route('peminjaman.index') }}" class="menu-card"><div class="mc-icon" style="background:#e6f7ef;color:#1a8a52"><i class="bi bi-journal-bookmark-fill"></i></div><div class="mc-label">Peminjaman</div><div class="mc-desc">Riwayat peminjaman</div></a></div>
        <div class="col-6"><a href="{{ route('prestasi.index') }}" class="menu-card"><div class="mc-icon" style="background:#fdf0fb;color:#8c1fa0"><i class="bi bi-trophy-fill"></i></div><div class="mc-label">Prestasi</div><div class="mc-desc">Pencapaian mahasiswa</div></a></div>
    </div>
    <div style="background:linear-gradient(135deg,#fff8e8,#fff);border:1px solid #f0d080;border-radius:12px;padding:1.2rem;text-align:center">
        <strong style="color:var(--navy);font-size:.85rem"><i class="bi bi-shield-lock-fill text-warning me-1"></i> Akses lebih lengkap setelah login</strong>
        <p style="font-size:.75rem;color:#8a9db0;margin:.3rem 0 .7rem">Login sebagai Admin, Petugas, atau Mahasiswa.</p>
        <a href="{{ route('login') }}" class="btn-topbar" style="display:inline-flex;background:var(--navy);color:#fff"><i class="bi bi-box-arrow-in-right"></i> Login Sekarang</a>
    </div>
</div>
@endauth

<footer>&copy; {{ date('Y') }} SIMAK — Sistem Informasi Mahasiswa</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>