class JenisPrestasi {
  final int id;
  final String namaJenis;

  JenisPrestasi({required this.id, required this.namaJenis});

  factory JenisPrestasi.fromJson(Map<String, dynamic> json) {
    return JenisPrestasi(
      id: json['id'],
      namaJenis: json['nama_jenis'] ?? '',
    );
  }
}

class TingkatPrestasi {
  final int id;
  final String namaTingkat;

  TingkatPrestasi({required this.id, required this.namaTingkat});

  factory TingkatPrestasi.fromJson(Map<String, dynamic> json) {
    return TingkatPrestasi(
      id: json['id'],
      namaTingkat: json['nama_tingkat'] ?? '',
    );
  }
}
