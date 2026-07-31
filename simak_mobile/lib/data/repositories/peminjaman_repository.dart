import '../../core/api/api_client.dart';
import '../models/paged_response.dart';
import '../models/peminjaman.dart';

class PeminjamanRepository {
  final ApiClient _api = ApiClient.instance;

  Future<PagedResponse<Peminjaman>> list({
    int page = 1,
    String search = '',
  }) async {
    final response = await _api.request(
      '/peminjaman',
      query: {
        'page': page,
        if (search.isNotEmpty) 'search': search,
      },
    ) as Map<String, dynamic>;

    return PagedResponse.fromJson(response, Peminjaman.fromJson);
  }

  Future<Peminjaman> show(int id) async {
    final response =
        await _api.request('/peminjaman/$id') as Map<String, dynamic>;
    return Peminjaman.fromJson(response['data'] as Map<String, dynamic>);
  }

  Future<Peminjaman> create(Map<String, dynamic> data) async {
    final response = await _api.request(
      '/peminjaman',
      method: 'POST',
      data: data,
    ) as Map<String, dynamic>;
    return Peminjaman.fromJson(response['data'] as Map<String, dynamic>);
  }

  Future<Peminjaman> pengembalian(int id) async {
    final response = await _api.request(
      '/peminjaman/$id/pengembalian',
      method: 'POST',
    ) as Map<String, dynamic>;
    return Peminjaman.fromJson(response['data'] as Map<String, dynamic>);
  }

  Future<void> delete(int id) async {
    await _api.request('/peminjaman/$id', method: 'DELETE');
  }
}
