import 'package:flutter/foundation.dart';

import '../../core/api/api_client.dart';
import '../models/user.dart';
import '../repositories/auth_repository.dart';

enum AuthStatus { unknown, unauthenticated, authenticated }

class AuthProvider extends ChangeNotifier {
  AuthProvider(this._repository) {
    ApiClient.instance.setUnauthorizedHandler(_forceLogout);
  }

  final AuthRepository _repository;

  AuthStatus _status = AuthStatus.unknown;
  User? _user;
  String? _error;

  AuthStatus get status => _status;
  User? get user => _user;
  String? get error => _error;
  bool get isAuthenticated => _status == AuthStatus.authenticated;

  Future<void> restoreSession() async {
    final user = await _repository.restoreSession();
    if (user != null) {
      _user = user;
      _status = AuthStatus.authenticated;
    } else {
      _status = AuthStatus.unauthenticated;
    }
    notifyListeners();
  }

  Future<bool> login(String email, String password) async {
    _error = null;
    notifyListeners();

    try {
      _user = await _repository.login(email, password);
      _status = AuthStatus.authenticated;
      notifyListeners();
      return true;
    } catch (e) {
      _error = e.toString();
      _status = AuthStatus.unauthenticated;
      notifyListeners();
      return false;
    }
  }

  Future<void> updateProfile({
    required String nama,
    required String email,
  }) async {
    if (_user == null) return;
    _user = User(
      id: _user!.id,
      nama: nama,
      email: email,
      role: _user!.role,
      nim: _user!.nim,
      mahasiswaId: _user!.mahasiswaId,
    );
    await _repository.updateStoredUser(nama: nama, email: email);
    notifyListeners();
  }

  Future<void> logout() async {
    await _repository.logout();
    _user = null;
    _status = AuthStatus.unauthenticated;
    notifyListeners();
  }

  void _forceLogout() {
    if (_status == AuthStatus.authenticated) {
      _repository.logout();
      _user = null;
      _status = AuthStatus.unauthenticated;
      notifyListeners();
    }
  }
}
