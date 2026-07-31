import '../../core/api/api_client.dart';
import '../models/buku.dart';
import '../models/paged_response.dart';

class BukuRepository {
  final ApiClient _api = ApiClient.instance;

  Future<PagedResponse<Buku>> list({
    int page = 1,
    String search = '',
  }) async {
    final response = await _api.request(
      '/buku',
      query: {
        'page': page,
        if (search.isNotEmpty) 'search': search,
      },
    ) as Map<String, dynamic>;

    return PagedResponse.fromJson(response, Buku.fromJson);
  }

  Future<List<Buku>> getAll() async {
    final all = <Buku>[];
    var page = 1;
    PagedResponse<Buku> result;
    do {
      result = await list(page: page);
      all.addAll(result.items);
      page++;
    } while (page <= result.lastPage);
    return all;
  }

  Future<Buku> show(int id) async {
    final response = await _api.request('/buku/$id') as Map<String, dynamic>;
    return Buku.fromJson(response['data'] as Map<String, dynamic>);
  }

  Future<Buku> create(Map<String, dynamic> data) async {
    final response = await _api.request(
      '/buku',
      method: 'POST',
      data: data,
    ) as Map<String, dynamic>;
    return Buku.fromJson(response['data'] as Map<String, dynamic>);
  }

  Future<Buku> update(int id, Map<String, dynamic> data) async {
    final response = await _api.request(
      '/buku/$id',
      method: 'PUT',
      data: data,
    ) as Map<String, dynamic>;
    return Buku.fromJson(response['data'] as Map<String, dynamic>);
  }

  Future<void> delete(int id) async {
    await _api.request('/buku/$id', method: 'DELETE');
  }
}
