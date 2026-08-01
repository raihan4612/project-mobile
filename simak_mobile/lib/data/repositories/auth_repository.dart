import 'dart:convert';

import 'package:shared_preferences/shared_preferences.dart';

import '../../core/api/api_client.dart';
import '../../core/api/api_exception.dart';
import '../models/user.dart';

class AuthRepository {
  static const _tokenKey = 'auth_token';
  static const _userKey = 'auth_user';

  final ApiClient _api = ApiClient.instance;

  Future<User> login(String nimOrEmail, String password) async {
    final raw = await _api.request(
      '/login',
      method: 'POST',
      data: {'nim': nimOrEmail, 'password': password},
    );

    final response = _asJsonMap(raw, endpoint: '/login');
    final userData = _asJsonMap(response['user'], endpoint: '/login', field: 'user');

    final token = response['token'];
    if (token is! String || token.isEmpty) {
      throw ApiException(
        message: 'Respons /login tidak valid: field "token" hilang atau bukan '
            'string (tipe: ${token == null ? 'null' : token.runtimeType}).',
      );
    }

    final user = User.fromJson(userData);

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
    try {
      return User.fromJson(
        _asJsonMap(jsonDecode(userRaw), endpoint: 'restore-session'),
      );
    } catch (_) {
      await prefs.remove(_tokenKey);
      await prefs.remove(_userKey);
      return null;
    }
  }

  Map<String, dynamic> _asJsonMap(
    Object? value, {
    required String endpoint,
    String field = 'respons',
  }) {
    if (value is Map<String, dynamic>) return value;
    final snippet = value == null
        ? 'null'
        : value.toString().replaceAll(RegExp(r'\s+'), ' ').trim();
    final truncated =
        snippet.length > 200 ? '${snippet.substring(0, 200)}…' : snippet;
    throw ApiException(
      message: 'Respons $endpoint tidak valid: field "$field" bukan objek JSON. '
          'Tipe: ${value == null ? 'null' : value.runtimeType}. '
          'Isi: $truncated',
    );
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
