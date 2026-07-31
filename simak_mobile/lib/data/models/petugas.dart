class Petugas {
  final int id;
  final String kodePetugas;
  final String nama;
  final String? email;
  final String? noHp;
  final String? jabatan;
  final String? status;
  final int peminjamanCount;

  Petugas({
    required this.id,
    required this.kodePetugas,
    required this.nama,
    this.email,
    this.noHp,
    this.jabatan,
    this.status,
    this.peminjamanCount = 0,
  });

  factory Petugas.fromJson(Map<String, dynamic> json) {
    return Petugas(
      id: json['id'],
      kodePetugas: json['kode_petugas'] ?? '',
      nama: json['nama'] ?? '',
      email: json['email'],
      noHp: json['no_hp'],
      jabatan: json['jabatan'],
      status: json['status'],
      peminjamanCount: json['peminjaman_count'] ?? 0,
    );
  }
}
