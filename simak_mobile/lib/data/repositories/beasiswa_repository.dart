import '../../core/api/api_client.dart';
import '../models/beasiswa.dart';
import '../models/paged_response.dart';
import '../models/program_beasiswa.dart';

class BeasiswaRepository {
  final ApiClient _api = ApiClient.instance;

  Future<PagedResponse<Beasiswa>> list({
    int page = 1,
    String search = '',
  }) async {
    final response = await _api.request(
      '/beasiswa',
      query: {
        'page': page,
        if (search.isNotEmpty) 'search': search,
      },
    ) as Map<String, dynamic>;

    return PagedResponse.fromJson(response, Beasiswa.fromJson);
  }

  Future<Beasiswa> show(int id) async {
    final response = await _api.request('/beasiswa/$id') as Map<String, dynamic>;
    return Beasiswa.fromJson(response['data'] as Map<String, dynamic>);
  }

  Future<Beasiswa> create(Map<String, dynamic> data) async {
    final response = await _api.request(
      '/beasiswa',
      method: 'POST',
      data: data,
    ) as Map<String, dynamic>;
    return Beasiswa.fromJson(response['data'] as Map<String, dynamic>);
  }

  Future<Beasiswa> update(int id, Map<String, dynamic> data) async {
    final response = await _api.request(
      '/beasiswa/$id',
      method: 'PUT',
      data: data,
    ) as Map<String, dynamic>;
    return Beasiswa.fromJson(response['data'] as Map<String, dynamic>);
  }

  Future<void> delete(int id) async {
    await _api.request('/beasiswa/$id', method: 'DELETE');
  }

  Future<String> hitungRekomendasi() async {
    final response = await _api.request(
      '/beasiswa/hitung-rekomendasi',
      method: 'POST',
    ) as Map<String, dynamic>;
    return response['message'] ?? 'Rekomendasi berhasil dihitung';
  }

  Future<PagedResponse<ProgramBeasiswa>> programBeasiswa({
    int page = 1,
    String search = '',
  }) async {
    final response = await _api.request(
      '/program-beasiswa',
      query: {
        'page': page,
        if (search.isNotEmpty) 'search': search,
      },
    ) as Map<String, dynamic>;

    return PagedResponse.fromJson(response, ProgramBeasiswa.fromJson);
  }

  Future<List<ProgramBeasiswa>> getAllProgramBeasiswa() async {
    final all = <ProgramBeasiswa>[];
    var page = 1;
    PagedResponse<ProgramBeasiswa> result;
    do {
      result = await programBeasiswa(page: page);
      all.addAll(result.items);
      page++;
    } while (page <= result.lastPage);
    return all;
  }
}
