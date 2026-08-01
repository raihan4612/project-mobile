class ApiConfig {
  ApiConfig._();

  static const String _overrideBaseUrl = String.fromEnvironment('API_URL');

  static const String _productionUrl = 'https://project-simak.infinityfreeapp.com/praktikum24/public/api';

  static String get baseUrl {
    if (_overrideBaseUrl.isNotEmpty) return _overrideBaseUrl;
    return _productionUrl;
  }
}
