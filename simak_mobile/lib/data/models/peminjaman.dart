import 'buku.dart';
import 'mahasiswa.dart';
import 'petugas.dart';

class Peminjaman {
  final int id;
  final String kodePeminjaman;
  final Mahasiswa? mahasiswa;
  final Buku? buku;
  final Petugas? petugas;
  final String? tanggalPinjam;
  final String? tanggalKembaliRencana;
  final String? tanggalKembaliAktual;
  final String? status;
  final String? denda;
  final String? catatan;

  Peminjaman({
    required this.id,
    required this.kodePeminjaman,
    this.mahasiswa,
    this.buku,
    this.petugas,
    this.tanggalPinjam,
    this.tanggalKembaliRencana,
    this.tanggalKembaliAktual,
    this.status,
    this.denda,
    this.catatan,
  });

  factory Peminjaman.fromJson(Map<String, dynamic> json) {
    return Peminjaman(
      id: json['id'],
      kodePeminjaman: json['kode_peminjaman'] ?? '',
      mahasiswa: json['mahasiswa'] != null
          ? Mahasiswa.fromJson(json['mahasiswa'] as Map<String, dynamic>)
          : null,
      buku: json['buku'] != null
          ? Buku.fromJson(json['buku'] as Map<String, dynamic>)
          : null,
      petugas: json['petugas'] != null
          ? Petugas.fromJson(json['petugas'] as Map<String, dynamic>)
          : null,
      tanggalPinjam: json['tanggal_pinjam'],
      tanggalKembaliRencana: json['tanggal_kembali_rencana'],
      tanggalKembaliAktual: json['tanggal_kembali_aktual'],
      status: json['status'],
      denda: json['denda']?.toString(),
      catatan: json['catatan'],
    );
  }

  bool get dipinjam => status == 'Dipinjam';
}
