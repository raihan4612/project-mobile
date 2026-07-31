class Buku {
  final int id;
  final String kodeBuku;
  final String judul;
  final String? pengarang;
  final String? penerbit;
  final String? tahunTerbit;
  final String? kategori;
  final int jumlahStok;
  final int jumlahTersedia;
  final String? deskripsi;
  final String? status;
  final int peminjamanCount;

  Buku({
    required this.id,
    required this.kodeBuku,
    required this.judul,
    this.pengarang,
    this.penerbit,
    this.tahunTerbit,
    this.kategori,
    this.jumlahStok = 0,
    this.jumlahTersedia = 0,
    this.deskripsi,
    this.status,
    this.peminjamanCount = 0,
  });

  factory Buku.fromJson(Map<String, dynamic> json) {
    return Buku(
      id: json['id'],
      kodeBuku: json['kode_buku'] ?? '',
      judul: json['judul'] ?? '',
      pengarang: json['pengarang'],
      penerbit: json['penerbit'],
      tahunTerbit: json['tahun_terbit'],
      kategori: json['kategori'],
      jumlahStok: json['jumlah_stok'] ?? 0,
      jumlahTersedia: json['jumlah_tersedia'] ?? 0,
      deskripsi: json['deskripsi'],
      status: json['status'],
      peminjamanCount: json['peminjaman_count'] ?? 0,
    );
  }

  bool get tersedia => status == 'Tersedia' && jumlahTersedia > 0;
}
