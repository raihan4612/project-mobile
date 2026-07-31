import '../../core/api/api_client.dart';
import '../models/dashboard_stats.dart';
import '../models/peminjaman.dart';
import '../models/prestasi.dart';

class DashboardData {
  final DashboardStats stats;
  final List<Prestasi> prestasiTerbaru;
  final List<Peminjaman> peminjamanTerbaru;

  DashboardData({
    required this.stats,
    required this.prestasiTerbaru,
    required this.peminjamanTerbaru,
  });
}

class DashboardRepository {
  final ApiClient _api = ApiClient.instance;

  Future<DashboardData> fetch() async {
    final response =
        await _api.request('/dashboard') as Map<String, dynamic>;

    final prestasi = (response['prestasi_terbaru'] as List? ?? [])
        .map((e) => Prestasi.fromJson(e as Map<String, dynamic>))
        .toList();

    final peminjaman = (response['peminjaman_terbaru'] as List? ?? [])
        .map((e) => Peminjaman.fromJson(e as Map<String, dynamic>))
        .toList();

    return DashboardData(
      stats: DashboardStats.fromJson(response),
      prestasiTerbaru: prestasi,
      peminjamanTerbaru: peminjaman,
    );
  }
}
