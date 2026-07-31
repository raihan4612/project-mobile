class PagedResponse<T> {
  final List<T> items;
  final int currentPage;
  final int lastPage;
  final int total;

  PagedResponse({
    required this.items,
    required this.currentPage,
    required this.lastPage,
    required this.total,
  });

  factory PagedResponse.fromJson(
    Map<String, dynamic> json,
    T Function(Map<String, dynamic>) fromJson,
  ) {
    final data = json['data'] as List? ?? [];
    final meta = json['meta'] as Map<String, dynamic>? ?? {};

    return PagedResponse(
      items: data
          .map((e) => fromJson(e as Map<String, dynamic>))
          .toList(growable: false),
      currentPage: meta['current_page'] ?? 1,
      lastPage: meta['last_page'] ?? 1,
      total: meta['total'] ?? data.length,
    );
  }
}
