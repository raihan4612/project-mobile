import 'package:flutter/foundation.dart';

import '../models/peminjaman.dart';
import '../repositories/peminjaman_repository.dart';

class PeminjamanProvider extends ChangeNotifier {
  PeminjamanProvider(this._repository);

  final PeminjamanRepository _repository;

  List<Peminjaman> _items = [];
  bool _loading = false;
  bool _loadingMore = false;
  String? _error;
  String _search = '';
  int _page = 1;
  int _lastPage = 1;
  bool _hasMore = false;

  List<Peminjaman> get items => _items;
  bool get loading => _loading;
  bool get loadingMore => _loadingMore;
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

  Future<void> pengembalian(int id) async {
    await _repository.pengembalian(id);
    await load(showLoader: false);
  }

  Future<void> delete(int id) async {
    await _repository.delete(id);
    _items.removeWhere((e) => e.id == id);
    notifyListeners();
  }
}
