class ProgramBeasiswa {
  final int id;
  final String namaBeasiswa;
  final String penyelenggara;
  final String? tahunAkademik;
  final String? jumlahDana;
  final int beasiswaCount;

  ProgramBeasiswa({
    required this.id,
    required this.namaBeasiswa,
    required this.penyelenggara,
    this.tahunAkademik,
    this.jumlahDana,
    this.beasiswaCount = 0,
  });

  factory ProgramBeasiswa.fromJson(Map<String, dynamic> json) {
    return ProgramBeasiswa(
      id: json['id'],
      namaBeasiswa: json['nama_beasiswa'] ?? '',
      penyelenggara: json['penyelenggara'] ?? '',
      tahunAkademik: json['tahun_akademik'],
      jumlahDana: json['jumlah_dana']?.toString(),
      beasiswaCount: json['beasiswa_count'] ?? 0,
    );
  }
}

class FuzzyHasil {
  final double? nilaiFuzzy;
  final String? hasilRekomendasi;

  FuzzyHasil({this.nilaiFuzzy, this.hasilRekomendasi});

  factory FuzzyHasil.fromJson(Map<String, dynamic>? json) {
    if (json == null) return FuzzyHasil();
    return FuzzyHasil(
      nilaiFuzzy: double.tryParse(json['nilai_fuzzy']?.toString() ?? ''),
      hasilRekomendasi: json['hasil_rekomendasi'],
    );
  }
}
