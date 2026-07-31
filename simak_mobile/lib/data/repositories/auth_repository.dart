import 'dart:convert';

import 'package:shared_preferences/shared_preferences.dart';

import '../../core/api/api_client.dart';
import '../models/user.dart';

class AuthRepository {
  static const _tokenKey = 'auth_token';
  static const _userKey = 'auth_user';

  final ApiClient _api = ApiClient.instance;

  Future<User> login(String nimOrEmail, String password) async {
    final response = await _api.request(
      '/login',
      method: 'POST',
      data: {'nim': nimOrEmail, 'password': password},
    ) as Map<String, dynamic>;

    final token = response['token'] as String;
    final user = User.fromJson(response['user'] as Map<String, dynamic>);

    _api.token = token;

    final prefs = await SharedPreferences.getInstance();
    await prefs.setString(_tokenKey, token);
    await prefs.setString(_userKey, jsonEncode({
      'id': user.id,
      'nama': user.nama,
      'email': user.email,
      'role': user.role,
      'nim': user.nim,
      'mahasiswa_id': user.mahasiswaId,
    }));

    return user;
  }

  Future<User?> restoreSession() async {
    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString(_tokenKey);
    if (token == null || token.isEmpty) return null;

    final userRaw = prefs.getString(_userKey);
    if (userRaw == null) return null;

    _api.token = token;
    return User.fromJson(jsonDecode(userRaw) as Map<String, dynamic>);
  }

  Future<void> updateStoredUser({
    required String nama,
    required String email,
  }) async {
    final prefs = await SharedPreferences.getInstance();
    final raw = prefs.getString(_userKey);
    if (raw == null) return;

    final json = jsonDecode(raw) as Map<String, dynamic>;
    json['nama'] = nama;
    json['email'] = email;
    await prefs.setString(_userKey, jsonEncode(json));
  }

  Future<void> logout() async {
    try {
      await _api.request('/logout', method: 'POST');
    } catch (_) {
      // token invalid/expired — tetap lanjut bersihkan lokal
    }

    _api.token = null;

    final prefs = await SharedPreferences.getInstance();
    await prefs.remove(_tokenKey);
    await prefs.remove(_userKey);
  }
}
