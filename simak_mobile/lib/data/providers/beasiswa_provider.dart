import 'package:flutter/foundation.dart';

import '../models/beasiswa.dart';
import '../models/program_beasiswa.dart';
import '../repositories/beasiswa_repository.dart';

class BeasiswaProvider extends ChangeNotifier {
  BeasiswaProvider(this._repository);

  final BeasiswaRepository _repository;

  List<Beasiswa> _items = [];
  List<ProgramBeasiswa> _programs = [];
  bool _loading = false;
  bool _loadingMore = false;
  bool _loadingPrograms = false;
  String? _error;
  String _search = '';
  int _page = 1;
  int _lastPage = 1;
  bool _hasMore = false;

  List<Beasiswa> get items => _items;
  List<ProgramBeasiswa> get programs => _programs;
  bool get loading => _loading;
  bool get loadingMore => _loadingMore;
  bool get loadingPrograms => _loadingPrograms;
  String? get error => _error;
  bool get hasMore => _hasMore;

  Future<void> load({bool showLoader = true}) async {
    if (showLoader) _loading = true;
    _error = null;
    notifyListeners();

    try {
      final result = await _repository.list(page: 1, search: _search);
      _items = result.items;
      _page = result.currentPage;
      _lastPage = result.lastPage;
      _hasMore = _page < _lastPage;
    } catch (e) {
      _error = e.toString();
    } finally {
      _loading = false;
      notifyListeners();
    }
  }

  Future<void> loadMore() async {
    if (_loadingMore || !_hasMore || _loading) return;
    _loadingMore = true;
    notifyListeners();

    try {
      final result = await _repository.list(
        page: _page + 1,
        search: _search,
      );
      _items.addAll(result.items);
      _page = result.currentPage;
      _lastPage = result.lastPage;
      _hasMore = _page < _lastPage;
    } catch (e) {
      _error = e.toString();
    } finally {
      _loadingMore = false;
      notifyListeners();
    }
  }

  void setSearch(String query) {
    if (_search == query) return;
    _search = query;
    load();
  }

  Future<void> loadPrograms() async {
    _loadingPrograms = true;
    notifyListeners();

    try {
      _programs = await _repository.getAllProgramBeasiswa();
    } catch (e) {
      _error = e.toString();
    } finally {
      _loadingPrograms = false;
      notifyListeners();
    }
  }

  Future<String> hitungRekomendasi() async {
    return _repository.hitungRekomendasi();
  }

  Future<void> delete(int id) async {
    await _repository.delete(id);
    _items.removeWhere((e) => e.id == id);
    notifyListeners();
  }
}
