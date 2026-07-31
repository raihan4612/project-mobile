import 'package:flutter/foundation.dart';

import '../models/dashboard_stats.dart';
import '../models/peminjaman.dart';
import '../models/prestasi.dart';
import '../repositories/dashboard_repository.dart';

class DashboardProvider extends ChangeNotifier {
  DashboardProvider(this._repository);

  final DashboardRepository _repository;

  bool _loading = true;
  String? _error;
  DashboardStats? _stats;
  List<Prestasi> _prestasiTerbaru = [];
  List<Peminjaman> _peminjamanTerbaru = [];

  bool get loading => _loading;
  String? get error => _error;
  DashboardStats? get stats => _stats;
  List<Prestasi> get prestasiTerbaru => _prestasiTerbaru;
  List<Peminjaman> get peminjamanTerbaru => _peminjamanTerbaru;

  Future<void> load() async {
    _loading = true;
    _error = null;
    notifyListeners();

    try {
      final data = await _repository.fetch();
      _stats = data.stats;
      _prestasiTerbaru = data.prestasiTerbaru;
      _peminjamanTerbaru = data.peminjamanTerbaru;
    } catch (e) {
      _error = e.toString();
    } finally {
      _loading = false;
      notifyListeners();
    }
  }
}
