import 'dart:io';

import 'package:dio/dio.dart';

import '../../core/api/api_client.dart';
import '../models/paged_response.dart';
import '../models/prestasi.dart';

class PrestasiRepository {
  final ApiClient _api = ApiClient.instance;

  Future<PagedResponse<Prestasi>> list({
    int page = 1,
    String search = '',
  }) async {
    final response = await _api.request(
      '/prestasi',
      query: {
        'page': page,
        if (search.isNotEmpty) 'search': search,
      },
    ) as Map<String, dynamic>;

    return PagedResponse.fromJson(response, Prestasi.fromJson);
  }

  Future<Prestasi> show(int id) async {
    final response = await _api.request('/prestasi/$id') as Map<String, dynamic>;
    return Prestasi.fromJson(response['data'] as Map<String, dynamic>);
  }

  Future<Prestasi> create({
    required Map<String, dynamic> data,
    File? sertifikat,
  }) async {
    final response = await _api.request(
      '/prestasi',
      method: 'POST',
      data: _buildPayload(data, sertifikat),
    ) as Map<String, dynamic>;
    return Prestasi.fromJson(response['data'] as Map<String, dynamic>);
  }

  Future<Prestasi> update(
    int id, {
    required Map<String, dynamic> data,
    File? sertifikat,
  }) async {
    final response = await _api.request(
      '/prestasi/$id',
      method: 'PUT',
      data: _buildPayload(data, sertifikat),
    ) as Map<String, dynamic>;
    return Prestasi.fromJson(response['data'] as Map<String, dynamic>);
  }

  Future<Prestasi> verifikasi({
    required int id,
    required String status,
    String? catatan,
  }) async {
    final response = await _api.request(
      '/prestasi/$id/verifikasi',
      method: 'POST',
      data: {
        'status_verifikasi': status,
        if (catatan != null && catatan.isNotEmpty) 'catatan': catatan,
      },
    ) as Map<String, dynamic>;
    return Prestasi.fromJson(response['data'] as Map<String, dynamic>);
  }

  Future<void> delete(int id) async {
    await _api.request('/prestasi/$id', method: 'DELETE');
  }

  FormData _buildPayload(Map<String, dynamic> data, File? sertifikat) {
    final form = FormData.fromMap(data);
    if (sertifikat != null) {
      form.files.add(MapEntry(
        'sertifikat',
        MultipartFile.fromFileSync(
          sertifikat.path,
          filename: sertifikat.path.split('/').last.split('\\').last,
        ),
      ));
    }
    return form;
  }
}
