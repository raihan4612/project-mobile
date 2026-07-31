import 'mahasiswa.dart';
import 'program_beasiswa.dart';

class Beasiswa {
  final int id;
  final Mahasiswa? mahasiswa;
  final ProgramBeasiswa? programBeasiswa;
  final String? status;
  final String? tanggalPengajuan;
  final String? keterangan;
  final FuzzyHasil? fuzzyHasil;

  Beasiswa({
    required this.id,
    this.mahasiswa,
    this.programBeasiswa,
    this.status,
    this.tanggalPengajuan,
    this.keterangan,
    this.fuzzyHasil,
  });

  factory Beasiswa.fromJson(Map<String, dynamic> json) {
    return Beasiswa(
      id: json['id'],
      mahasiswa: json['mahasiswa'] != null
          ? Mahasiswa.fromJson(json['mahasiswa'] as Map<String, dynamic>)
          : null,
      programBeasiswa: json['program_beasiswa'] != null
          ? ProgramBeasiswa.fromJson(
              json['program_beasiswa'] as Map<String, dynamic>)
          : null,
      status: json['status'],
      tanggalPengajuan: json['tanggal_pengajuan'],
      keterangan: json['keterangan'],
      fuzzyHasil: FuzzyHasil.fromJson(
        json['fuzzy_hasil'] as Map<String, dynamic>?,
      ),
    );
  }
}
