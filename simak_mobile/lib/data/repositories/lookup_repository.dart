import '../../core/api/api_client.dart';
import '../models/jenis_tingkat_prestasi.dart';
import '../models/paged_response.dart';
import '../models/petugas.dart';

class LookupRepository {
  final ApiClient _api = ApiClient.instance;

  Future<List<JenisPrestasi>> getJenisPrestasi() async {
    return _fetchAll('/jenis-prestasi', JenisPrestasi.fromJson);
  }

  Future<List<TingkatPrestasi>> getTingkatPrestasi() async {
    return _fetchAll('/tingkat-prestasi', TingkatPrestasi.fromJson);
  }

  Future<List<Petugas>> getPetugas() async {
    return _fetchAll('/petugas', Petugas.fromJson);
  }

  Future<List<T>> _fetchAll<T>(
    String path,
    T Function(Map<String, dynamic>) fromJson,
  ) async {
    final all = <T>[];
    var page = 1;
    PagedResponse<T>? result;

    do {
      final response =
          await _api.request(path, query: {'page': page}) as Map<String, dynamic>;
      result = PagedResponse.fromJson(response, fromJson);
      all.addAll(result.items);
      page++;
    } while (result.lastPage > 1 && page <= result.lastPage);

    return all;
  }
}
