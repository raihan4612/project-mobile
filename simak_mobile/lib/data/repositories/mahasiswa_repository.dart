import '../../core/api/api_client.dart';
import '../models/mahasiswa.dart';
import '../models/paged_response.dart';

class MahasiswaRepository {
  final ApiClient _api = ApiClient.instance;

  Future<PagedResponse<Mahasiswa>> list({
    int page = 1,
    String search = '',
  }) async {
    final response = await _api.request(
      '/mahasiswa',
      query: {
        'page': page,
        if (search.isNotEmpty) 'search': search,
      },
    ) as Map<String, dynamic>;

    return PagedResponse.fromJson(response, Mahasiswa.fromJson);
  }

  Future<List<Mahasiswa>> getAll() async {
    final all = <Mahasiswa>[];
    var page = 1;
    PagedResponse<Mahasiswa> result;
    do {
      result = await list(page: page);
      all.addAll(result.items);
      page++;
    } while (page <= result.lastPage);
    return all;
  }

  Future<Mahasiswa> show(int id) async {
    final response = await _api.request('/mahasiswa/$id') as Map<String, dynamic>;
    return Mahasiswa.fromJson(response['data'] as Map<String, dynamic>);
  }

  Future<Mahasiswa> create(Map<String, dynamic> data) async {
    final response = await _api.request(
      '/mahasiswa',
      method: 'POST',
      data: data,
    ) as Map<String, dynamic>;
    return Mahasiswa.fromJson(response['data'] as Map<String, dynamic>);
  }

  Future<Mahasiswa> update(int id, Map<String, dynamic> data) async {
    final response = await _api.request(
      '/mahasiswa/$id',
      method: 'PUT',
      data: data,
    ) as Map<String, dynamic>;
    return Mahasiswa.fromJson(response['data'] as Map<String, dynamic>);
  }

  Future<void> delete(int id) async {
    await _api.request('/mahasiswa/$id', method: 'DELETE');
  }
}
