import 'package:dio/dio.dart';

import 'api_config.dart';
import 'api_exception.dart';

class ApiClient {
  ApiClient._() {
    _dio = Dio(
      BaseOptions(
        baseUrl: ApiConfig.baseUrl,
        connectTimeout: const Duration(seconds: 15),
        receiveTimeout: const Duration(seconds: 30),
        headers: {'Accept': 'application/json'},
      ),
    );

    _dio.interceptors.add(
      InterceptorsWrapper(
        onRequest: (options, handler) {
          final token = this.token;
          if (token != null && token.isNotEmpty) {
            options.headers['Authorization'] = 'Bearer $token';
          }
          handler.next(options);
        },
        onError: (error, handler) {
          if (error.response?.statusCode == 401 && _onUnauthorized != null) {
            _onUnauthorized!();
          }
          handler.next(error);
        },
      ),
    );
  }

  static final ApiClient instance = ApiClient._();

  late final Dio _dio;
  String? token;
  void Function()? _onUnauthorized;

  Dio get dio => _dio;

  void setUnauthorizedHandler(void Function()? handler) {
    _onUnauthorized = handler;
  }

  Future<dynamic> request(
    String path, {
    String method = 'GET',
    Map<String, dynamic>? query,
    Object? data,
  }) async {
    try {
      final options = Options(method: method);

      final response = await _dio.request<dynamic>(
        path,
        queryParameters: query,
        data: data,
        options: options,
      );

      return response.data;
    } on DioException catch (e) {
      throw ApiException.fromDio(e);
    }
  }
}
