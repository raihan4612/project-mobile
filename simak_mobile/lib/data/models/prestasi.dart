import 'jenis_tingkat_prestasi.dart';
import 'mahasiswa.dart';

class Prestasi {
  final int id;
  final Mahasiswa? mahasiswa;
  final JenisPrestasi? jenis;
  final TingkatPrestasi? tingkat;
  final String? namaLomba;
  final String? penyelenggara;
  final String? tanggal;
  final String? juara;
  final String? sertifikat;
  final String? statusVerifikasi;

  Prestasi({
    required this.id,
    this.mahasiswa,
    this.jenis,
    this.tingkat,
    this.namaLomba,
    this.penyelenggara,
    this.tanggal,
    this.juara,
    this.sertifikat,
    this.statusVerifikasi,
  });

  factory Prestasi.fromJson(Map<String, dynamic> json) {
    return Prestasi(
      id: json['id'],
      mahasiswa: json['mahasiswa'] != null
          ? Mahasiswa.fromJson(json['mahasiswa'] as Map<String, dynamic>)
          : null,
      jenis: json['jenis'] != null
          ? JenisPrestasi.fromJson(json['jenis'] as Map<String, dynamic>)
          : null,
      tingkat: json['tingkat'] != null
          ? TingkatPrestasi.fromJson(json['tingkat'] as Map<String, dynamic>)
          : null,
      namaLomba: json['nama_lomba'],
      penyelenggara: json['penyelenggara'],
      tanggal: json['tanggal'],
      juara: json['juara'],
      sertifikat: json['sertifikat'],
      statusVerifikasi: json['status_verifikasi'],
    );
  }
}
