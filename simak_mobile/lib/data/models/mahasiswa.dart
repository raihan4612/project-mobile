class Mahasiswa {
  final int id;
  final String nim;
  final String nama;
  final String? jenisKelamin;
  final String? tanggalLahir;
  final String? tempatLahir;
  final String? alamat;
  final String? kota;
  final String? provinsi;
  final String? kodePos;
  final String? noHp;
  final String? email;
  final String? prodi;
  final String? fakultas;
  final int? semester;
  final String? tahunMasuk;
  final String? status;
  final double? ipk;
  final String? foto;
  final int peminjamanCount;
  final int prestasiCount;

  Mahasiswa({
    required this.id,
    required this.nim,
    required this.nama,
    this.jenisKelamin,
    this.tanggalLahir,
    this.tempatLahir,
    this.alamat,
    this.kota,
    this.provinsi,
    this.kodePos,
    this.noHp,
    this.email,
    this.prodi,
    this.fakultas,
    this.semester,
    this.tahunMasuk,
    this.status,
    this.ipk,
    this.foto,
    this.peminjamanCount = 0,
    this.prestasiCount = 0,
  });

  factory Mahasiswa.fromJson(Map<String, dynamic> json) {
    return Mahasiswa(
      id: json['id'],
      nim: json['nim'] ?? '',
      nama: json['nama'] ?? '',
      jenisKelamin: json['jenis_kelamin'],
      tanggalLahir: json['tanggal_lahir'],
      tempatLahir: json['tempat_lahir'],
      alamat: json['alamat'],
      kota: json['kota'],
      provinsi: json['provinsi'],
      kodePos: json['kode_pos'],
      noHp: json['no_hp'],
      email: json['email'],
      prodi: json['prodi'],
      fakultas: json['fakultas'],
      semester: json['semester'],
      tahunMasuk: json['tahun_masuk'],
      status: json['status'],
      ipk: double.tryParse(json['ipk']?.toString() ?? ''),
      foto: json['foto'],
      peminjamanCount: json['peminjaman_count'] ?? 0,
      prestasiCount: json['prestasi_count'] ?? 0,
    );
  }
}
