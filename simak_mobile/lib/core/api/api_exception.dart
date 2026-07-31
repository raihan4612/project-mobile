import 'package:dio/dio.dart';

class ApiException implements Exception {
  final int? statusCode;
  final String message;
  final Map<String, dynamic>? errors;

  ApiException({this.statusCode, required this.message, this.errors});

  factory ApiException.fromDio(DioException error) {
    final response = error.response;
    final data = response?.data;

    if (data is Map<String, dynamic>) {
      final message = data['message'] ?? error.message ?? 'Terjadi kesalahan';
      final errors = data['errors'];
      if (errors is Map<String, dynamic>) {
        final first = errors.values.expand((e) => e is List ? e : [e]).firstOrNull;
        if (first != null) {
          return ApiException(
            statusCode: response?.statusCode,
            message: '$first',
            errors: errors.cast<String, dynamic>(),
          );
        }
      }
      return ApiException(
        statusCode: response?.statusCode,
        message: message.toString(),
      );
    }

    return ApiException(
      statusCode: response?.statusCode,
      message: error.message ?? 'Terjadi kesalahan',
    );
  }

  bool get isUnauthorized => statusCode == 401;

  @override
  String toString() => message;
}
