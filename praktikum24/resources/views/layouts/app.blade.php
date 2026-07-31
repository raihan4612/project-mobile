<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIMAK')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --sidebar-from: #1a3c6e;
            --sidebar-to:   #2563a8;
        }
        body { background-color: #f0f2f5; font-size: 0.9rem; }

        /* ── Sidebar ─────────────────────────────────────────── */
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, var(--sidebar-from) 0%, var(--sidebar-to) 100%);
            transition: transform .3s ease;
        }
        .sidebar-brand {
            padding: 1.2rem 1rem .9rem;
            color: #fff;
            font-weight: 700;
            font-size: 1.05rem;
            border-bottom: 1px solid rgba(255,255,255,.15);
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .4rem;
            transition: opacity .18s;
        }
        .sidebar-brand:hover { opacity: .85; color: #fff; }

        /* ── User info block (tepat di bawah brand) ── */
        .sidebar-user {
            padding: .75rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,.15);
            margin-bottom: .5rem;
        }
        .sidebar-user .user-name {
            font-size: .82rem;
            font-weight: 600;
            color: #fff;
            line-height: 1.2;
        }
        .sidebar-user .user-meta {
            display: flex;
            align-items: center;
            gap: .5rem;
            margin-top: .35rem;
            flex-wrap: wrap;
        }
        .sidebar-user .btn-logout {
            font-size: .75rem;
            padding: .2rem .6rem;
            line-height: 1.4;
        }

        .sidebar .nav-link {
            color: rgba(255,255,255,.8);
            border-radius: 8px;
            margin-bottom: 2px;
            padding: .45rem .75rem;
            transition: background .18s, color .18s;
            font-size: .875rem;
        }
        .sidebar .nav-link i { margin-right: 7px; width: 16px; text-align: center; }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: rgba(255,255,255,.2);
            color: #fff;
        }
        .sidebar-section {
            color: rgba(255,255,255,.45);
            font-size: .7rem;
            text-transform: uppercase;
            letter-spacing: .08em;
            padding: .6rem .75rem .2rem;
            font-weight: 600;
        }

        /* ── Sidebar Toggle (mobile) ─────────────────────────── */
        .sidebar-toggle {
            display: none;
            position: fixed;
            top: 10px;
            left: 10px;
            z-index: 1050;
            background: var(--sidebar-from);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: .4rem .6rem;
            font-size: 1.2rem;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0,0,0,.2);
            transition: background .18s;
        }
        .sidebar-toggle:hover { background: var(--sidebar-to); }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.4);
            z-index: 1020;
        }
        .sidebar-overlay.show { display: block; }

        /* ── Main Content ────────────────────────────────────── */
        .main-content { padding: 1.75rem 2rem; }

        /* ── Cards ───────────────────────────────────────────── */
        .card { border: none; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,.07); }
        .card-header { border-radius: 12px 12px 0 0 !important; font-weight: 600; font-size: .875rem; }

        /* ── Tables ──────────────────────────────────────────── */
        .table thead th {
            background-color: var(--sidebar-from);
            color: #fff;
            border: none;
            font-size: .8rem;
            font-weight: 600;
            padding: .65rem .75rem;
        }
        .table td { vertical-align: middle; padding: .55rem .75rem; font-size: .85rem; }
        .table-hover tbody tr:hover { background-color: rgba(37,99,168,.05); }

        /* ── Buttons ─────────────────────────────────────────── */
        .btn-primary { background-color: #2563a8; border-color: #2563a8; }
        .btn-primary:hover { background-color: var(--sidebar-from); border-color: var(--sidebar-from); }

        /* ── Badge role ──────────────────────────────────────── */
        .badge-admin    { background-color: #dc3545; }
        .badge-petugas  { background-color: #fd7e14; }
        .badge-user     { background-color: #0d6efd; }
        .badge-guest    { background-color: #6c757d; }

        /* ── Notifikasi Badge ────────────────────────────────── */
        .notif-badge {
            position: absolute;
            top: -4px;
            right: -8px;
            font-size: .6rem;
            padding: 2px 5px;
            border-radius: 50%;
            min-width: 16px;
            text-align: center;
        }
        .notif-dropdown {
            max-height: 400px;
            overflow-y: auto;
            width: 320px;
        }
        .notif-dropdown .dropdown-item {
            white-space: normal;
            font-size: .82rem;
            padding: .5rem .75rem;
            border-bottom: 1px solid #f0f0f0;
        }
        .notif-dropdown .dropdown-item:last-child { border-bottom: none; }
        .notif-dropdown .dropdown-item.unread { background-color: #eef2ff; }
        .notif-dropdown .notif-time {
            font-size: .7rem;
            color: #999;
        }

        /* ── Pagination ──────────────────────────────────────── */
        .pagination .page-link {
            padding: .25rem .6rem;
            font-size: .8rem;
        }
        .pagination li:first-child .page-link,
        .pagination li:last-child .page-link {
            font-size: .7rem;
            line-height: 1.2;
        }

        /* ── Search / Filter ─────────────────────────────────── */
        .search-box { min-width: 200px; }
        .filter-box { min-width: 150px; }

        /* ── Responsive ──────────────────────────────────────── */
        @@media (max-width: 767.98px) {
            .sidebar-toggle { display: block; }

            .sidebar {
                position: fixed;
                top: 0;
                left: 0;
                z-index: 1030;
                width: 260px;
                transform: translateX(-100%);
                overflow-y: auto;
            }
            .sidebar.show { transform: translateX(0); }

            .main-content {
                padding: 1rem;
                margin-left: 0;
                padding-top: 3.5rem;
            }
            .search-box { min-width: 140px; }
            .filter-box { min-width: 120px; }
            .table-responsive { font-size: .8rem; }
            .table td, .table th { padding: .4rem .5rem; }
        }

        @@media (min-width: 768px) {
            .sidebar { transform: translateX(0) !important; }
        }
    </style>
</head>
<body>

{{-- ── Sidebar Toggle Button (mobile) ── --}}
<button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
    <i class="bi bi-list"></i>
</button>

{{-- ── Sidebar Overlay (mobile) ── --}}
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="container-fluid">
    <div class="row">

        {{-- ── Sidebar ──────────────────────────────────────── --}}
        <div class="col-md-2 p-0 sidebar" id="sidebar">

            {{-- Brand / Logo → klik ke dashboard --}}
            <a href="{{ route('dashboard') }}" class="sidebar-brand">
                <i class="bi bi-mortarboard-fill"></i> SIMAK
            </a>

            {{-- ── User info tepat di bawah brand ── --}}
            <div class="sidebar-user">
                @auth
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-person-circle text-white" style="font-size:1.6rem;"></i>
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="user-name text-truncate">{{ auth()->user()->nama }}</div>
                            @php
                                $badgeClass = match(auth()->user()->role) {
                                    'admin'   => 'badge-admin',
                                    'petugas' => 'badge-petugas',
                                    'user'    => 'badge-user',
                                    default   => 'badge-guest',
                                };
                            @endphp
                            <div class="user-meta">
                                <span class="badge {{ $badgeClass }}" style="font-size:.65rem;">
                                    {{ ucfirst(auth()->user()->role) }}
                                </span>

                                <form action="{{ route('logout') }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-light btn-logout">
                                        <i class="bi bi-box-arrow-left"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-person-circle text-white" style="font-size:1.6rem;"></i>
                        <div>
                            <div class="user-name">Tamu</div>
                            <div class="user-meta">
                                <span class="badge badge-guest" style="font-size:.65rem;">Guest</span>
                                <a href="{{ route('login') }}" class="btn btn-outline-light btn-logout">
                                    <i class="bi bi-box-arrow-in-right"></i> Login
                                </a>
                            </div>
                        </div>
                    </div>
                @endauth
            </div>

            {{-- ── Nav Menu ── --}}
            <ul class="nav flex-column px-2">

                <li><div class="sidebar-section">Data Utama</div></li>

                <li class="nav-item">
                    <a href="{{ route('data-mahasiswa') }}"
                       class="nav-link {{ request()->is('data-mahasiswa*') ? 'active' : '' }}">
                        <i class="bi bi-people-fill"></i> Mahasiswa
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('buku.index') }}"
                       class="nav-link {{ request()->is('buku*') ? 'active' : '' }}">
                        <i class="bi bi-book-fill"></i> Buku
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('peminjaman.index') }}"
                       class="nav-link {{ request()->is('peminjaman*') ? 'active' : '' }}">
                        <i class="bi bi-journal-bookmark-fill"></i> Peminjaman
                    </a>
                </li>

                {{-- Petugas: hanya Admin & Petugas --}}
                @auth
                    @if(auth()->user()->isAdmin() || auth()->user()->role === 'petugas')
                    <li class="nav-item">
                        <a href="{{ route('petugas.index') }}"
                           class="nav-link {{ request()->is('petugas*') ? 'active' : '' }}">
                            <i class="bi bi-person-workspace"></i> Petugas
                        </a>
                    </li>
                    @endif
                @endauth

                <li><div class="sidebar-section mt-1">Prestasi</div></li>
                <li class="nav-item">
                    <a href="{{ route('prestasi.index') }}"
                       class="nav-link {{ request()->is('prestasi') || request()->is('prestasi/*') ? 'active' : '' }}">
                        <i class="bi bi-trophy-fill"></i> Prestasi Mahasiswa
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('jenis-prestasi.index') }}"
                       class="nav-link {{ request()->is('jenis-prestasi*') ? 'active' : '' }}">
                        <i class="bi bi-tag-fill"></i> Jenis Prestasi
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('tingkat-prestasi.index') }}"
                       class="nav-link {{ request()->is('tingkat-prestasi*') ? 'active' : '' }}">
                        <i class="bi bi-bar-chart-fill"></i> Tingkat Prestasi
                    </a>
                </li>
                <li><div class="sidebar-section mt-1">Beasiswa</div></li>
                <li class="nav-item">
                    <a href="{{ route('program-beasiswa.index') }}"
                       class="nav-link {{ request()->is('program-beasiswa*') ? 'active' : '' }}">
                        <i class="bi bi-award-fill"></i> Program Beasiswa
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('beasiswa.index') }}"
                       class="nav-link {{ request()->is('beasiswa*') && !request()->is('program-beasiswa*') ? 'active' : '' }}">
                        <i class="bi bi-file-text-fill"></i> Pengajuan Beasiswa
                    </a>
                </li>

                {{-- Profile --}}
                @auth
                <li><div class="sidebar-section mt-1">Akun</div></li>
                <li class="nav-item">
                    <a href="{{ route('profile.index') }}"
                       class="nav-link {{ request()->is('profile*') ? 'active' : '' }}">
                        <i class="bi bi-person-gear"></i> Profil Saya
                    </a>
                </li>
                @endauth



            </ul>
        </div>

        {{-- ── Main Content ──────────────────────────────────── --}}
        <div class="col-md-10 main-content" id="mainContent">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show py-2" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show py-2" role="alert">
                    <i class="bi bi-x-circle-fill me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var sidebar = document.getElementById('sidebar');
        var toggleBtn = document.getElementById('sidebarToggle');
        var overlay = document.getElementById('sidebarOverlay');

        function toggleSidebar() {
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
            var icon = toggleBtn.querySelector('i');
            icon.classList.toggle('bi-list');
            icon.classList.toggle('bi-x');
        }

        if (toggleBtn) {
            toggleBtn.addEventListener('click', toggleSidebar);
        }
        if (overlay) {
            overlay.addEventListener('click', toggleSidebar);
        }

        // Tutup sidebar setelah klik link (mobile)
        var navLinks = sidebar.querySelectorAll('.nav-link');
        navLinks.forEach(function (link) {
            link.addEventListener('click', function () {
                if (window.innerWidth < 768) {
                    sidebar.classList.remove('show');
                    overlay.classList.remove('show');
                    var icon = toggleBtn.querySelector('i');
                    icon.classList.add('bi-list');
                    icon.classList.remove('bi-x');
                }
            });
        });

        // Notifikasi: mark as read saat diklik
        document.querySelectorAll('.notif-item').forEach(function (el) {
            el.addEventListener('click', function (e) {
                var readUrl = this.dataset.readUrl;
                var targetUrl = this.getAttribute('href');
                if (!readUrl) return;

                e.preventDefault();

                fetch(readUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                }).then(function (res) {
                    if (res.ok) {
                        el.classList.remove('unread');
                        var badge = el.closest('.dropdown').querySelector('.notif-badge');
                        if (badge) {
                            var count = parseInt(badge.textContent);
                            if (count > 1) {
                                badge.textContent = count - 1;
                            } else {
                                badge.remove();
                            }
                        }
                    }
                }).catch(function (err) { console.error('Notif mark read error:', err); })
                .finally(function () {
                    if (targetUrl && targetUrl !== '#') {
                        window.location.href = targetUrl;
                    } else {
                        var dropdown = el.closest('.dropdown');
                        if (dropdown) {
                            var btn = dropdown.querySelector('[data-bs-toggle="dropdown"]');
                            if (btn) {
                                var dd = bootstrap.Dropdown.getInstance(btn);
                                if (dd) dd.hide();
                            }
                        }
                    }
                });
            });
        });
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
@yield('scripts')
</body>
</html>
