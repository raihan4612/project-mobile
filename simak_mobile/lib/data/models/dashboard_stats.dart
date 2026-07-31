class DashboardStats {
  final int mahasiswa;
  final int buku;
  final int peminjaman;
  final int prestasi;
  final int beasiswa;
  final int programBeasiswa;

  final int peminjamanDipinjam;
  final int peminjamanDikembalikan;
  final int peminjamanTerlambat;

  final int prestasiPending;
  final int prestasiDisetujui;
  final int prestasiDitolak;

  final int beasiswaDiajukan;
  final int beasiswaDisetujui;
  final int beasiswaDitolak;

  DashboardStats({
    required this.mahasiswa,
    required this.buku,
    required this.peminjaman,
    required this.prestasi,
    required this.beasiswa,
    required this.programBeasiswa,
    required this.peminjamanDipinjam,
    required this.peminjamanDikembalikan,
    required this.peminjamanTerlambat,
    required this.prestasiPending,
    required this.prestasiDisetujui,
    required this.prestasiDitolak,
    required this.beasiswaDiajukan,
    required this.beasiswaDisetujui,
    required this.beasiswaDitolak,
  });

  factory DashboardStats.fromJson(Map<String, dynamic> json) {
    final statistik = json['statistik'] as Map<String, dynamic>? ?? {};
    final pj = json['peminjaman_status'] as Map<String, dynamic>? ?? {};
    final pr = json['prestasi_status'] as Map<String, dynamic>? ?? {};
    final bs = json['beasiswa_status'] as Map<String, dynamic>? ?? {};

    return DashboardStats(
      mahasiswa: statistik['mahasiswa'] ?? 0,
      buku: statistik['buku'] ?? 0,
      peminjaman: statistik['peminjaman'] ?? 0,
      prestasi: statistik['prestasi'] ?? 0,
      beasiswa: statistik['beasiswa'] ?? 0,
      programBeasiswa: statistik['program_beasiswa'] ?? 0,
      peminjamanDipinjam: pj['dipinjam'] ?? 0,
      peminjamanDikembalikan: pj['dikembalikan'] ?? 0,
      peminjamanTerlambat: pj['terlambat'] ?? 0,
      prestasiPending: pr['pending'] ?? 0,
      prestasiDisetujui: pr['disetujui'] ?? 0,
      prestasiDitolak: pr['ditolak'] ?? 0,
      beasiswaDiajukan: bs['diajukan'] ?? 0,
      beasiswaDisetujui: bs['disetujui'] ?? 0,
      beasiswaDitolak: bs['ditolak'] ?? 0,
    );
  }
}
