<p align="center">
  <img src="landingpage/img/icon.png" alt="SIMAK" width="120">
</p>

<h1 align="center">SIMAK — Sistem Informasi Mahasiswa</h1>

<p align="center">
  Proyek monorepo aplikasi <b>Sistem Informasi Mahasiswa</b> yang terdiri dari aplikasi mobile (Flutter),
  backend/web admin (Laravel), dan halaman landing page.
</p>

---

## Daftar Isi

- [Struktur Project](#struktur-project)
- [Komponen](#komponen)
- [Alur Arsitektur](#alur-arsitektur)
- [Panduan Menjalankan](#panduan-menjalankan)
- [Akun Uji Coba](#akun-uji-coba)
- [Teknologi](#teknologi)
- [Fitur Utama](#fitur-utama)

## Struktur Project

```
project-mobile/
├── landingpage/     # Landing page statis (HTML/CSS) + file APK unduhan
├── simak_mobile/    # Aplikasi mobile Android (Flutter)
└── praktikum24/     # Backend REST API + Web Admin (Laravel)
```

## Komponen

### 1. `landingpage/`

Halaman web responsif (HTML/CSS murni) sebagai gerbang promosi & unduhan aplikasi.
Berisi penjelasan fitur, screenshot aplikasi, dokumentasi arsitektur/ERD/API, dan file
`downloads/simak-mobile.apk`.

### 2. `simak_mobile/`

Aplikasi mobile berbasis **Flutter** untuk Android. Mengakses data akademik, katalog &
peminjaman buku perpustakaan, input prestasi, pengajuan beasiswa, dan profil pengguna.

- [Lihat detail README aplikasi mobile](simak_mobile/README.md)

### 3. `praktikum24/`

Backend **Laravel** yang menyediakan REST API (dengan autentikasi token Sanctum) sekaligus
Web Admin berbasis Blade + Bootstrap. Memuat logika **Fuzzy Mamdani** untuk rekomendasi beasiswa.

- [Lihat detail README backend](praktikum24/README.md)

## Alur Arsitektur

```
Aplikasi Mobile (simak_mobile — Flutter)
        │  HTTPS · JSON API (token Sanctum)
        ▼
Backend (praktikum24 — Laravel 13)
        │
        ▼
Database MySQL
```

Landing page (`landingpage/`) mengarahkan pengguna ke unduhan APK aplikasi mobile dan
menyediakan tombol menuju Web Admin `praktikum24`.

## Panduan Menjalankan

### 1. Backend (praktikum24)

```bash
cd praktikum24
composer install
cp .env.example .env    # sesuaikan DB_DATABASE, DB_USERNAME, DB_PASSWORD
php artisan key:generate
php artisan migrate --seed
php artisan serve       # API aktif di http://localhost:8000
```

### 2. Aplikasi Mobile (simak_mobile)

```bash
cd simak_mobile
flutter pub get
flutter run              # atau: flutter build apk --release
```

> Perangkat fisik butuh alamat API server:
> `flutter build apk --release --dart-define=API_URL=http://IP-SERVER:8000/api`

### 3. Landing Page

Buka `landingpage/index.html` melalui web server (mis. Laragon) di
`http://localhost/project-mobile/landingpage/`.

## Akun Uji Coba

| Peran | Login | Password |
| --- | --- | --- |
| Admin | `admin@simak.com` | `admin123` |
| Petugas | `petugas@simak.com` | `petugas123` |
| Mahasiswa | NIM | `password` |

## Teknologi

| Komponen | Teknologi |
| --- | --- |
| Aplikasi Mobile | Flutter, Dart, Provider, Dio |
| Backend / Web Admin | Laravel 13, PHP 8.3, Blade, Bootstrap 5 |
| Database | MySQL |
| Autentikasi API | Laravel Sanctum (Bearer token) |
| Algoritma | Fuzzy Mamdani (rekomendasi beasiswa) |
| Landing Page | HTML, CSS (responsif) |

## Fitur Utama

- Dashboard statistik akademik real-time
- Katalog & peminjaman buku dengan denda otomatis (Rp 1.000/hari)
- Input & verifikasi prestasi berjenjang (dengan unggah sertifikat)
- Pengajuan & rekomendasi beasiswa berbasis logika Fuzzy Mamdani
- Hak akses per peran (RBAC): Admin, Petugas, Mahasiswa, Guest
- Notifikasi pengguna & profil akun
