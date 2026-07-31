class User {
  final int id;
  final String nama;
  final String email;
  final String role;
  final String? nim;
  final int? mahasiswaId;

  User({
    required this.id,
    required this.nama,
    required this.email,
    required this.role,
    this.nim,
    this.mahasiswaId,
  });

  factory User.fromJson(Map<String, dynamic> json) {
    return User(
      id: json['id'],
      nama: json['nama'] ?? '',
      email: json['email'] ?? '',
      role: json['role'] ?? 'guest',
      nim: json['nim'],
      mahasiswaId: json['mahasiswa_id'],
    );
  }

  bool get isAdmin => role == 'admin';
  bool get isPetugas => role == 'petugas';
  bool get isMahasiswa => role == 'user';
}
