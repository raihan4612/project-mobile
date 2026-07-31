import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../../core/theme/app_theme.dart';
import '../../core/widgets/gradient_background.dart';
import '../../core/widgets/status_views.dart';
import '../../data/models/prestasi.dart';
import '../../data/providers/auth_provider.dart';
import '../../data/providers/prestasi_provider.dart';
import 'prestasi_detail_screen.dart';
import 'prestasi_form_screen.dart';

class PrestasiListScreen extends StatefulWidget {
  const PrestasiListScreen({super.key});

  @override
  State<PrestasiListScreen> createState() => _PrestasiListScreenState();
}

class _PrestasiListScreenState extends State<PrestasiListScreen> {
  final _searchController = TextEditingController();
  final _scrollController = ScrollController();

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      if (mounted) context.read<PrestasiProvider>().load();
    });
    _scrollController.addListener(_onScroll);
  }

  @override
  void dispose() {
    _searchController.dispose();
    _scrollController.dispose();
    super.dispose();
  }

  void _onScroll() {
    if (_scrollController.position.pixels >=
        _scrollController.position.maxScrollExtent - 200) {
      context.read<PrestasiProvider>().loadMore();
    }
  }

  @override
  Widget build(BuildContext context) {
    final provider = context.watch<PrestasiProvider>();
    final isMahasiswa =
        context.watch<AuthProvider>().user?.isMahasiswa ?? false;

    return Scaffold(
      appBar: GradientAppBar(title: isMahasiswa ? 'Prestasi Saya' : 'Prestasi'),
      body: Column(
        children: [
          Padding(
            padding: const EdgeInsets.all(16),
            child: TextField(
              controller: _searchController,
              onChanged: (v) => context.read<PrestasiProvider>().setSearch(v),
              decoration: InputDecoration(
                hintText: 'Cari lomba / mahasiswa...',
                prefixIcon: const Icon(Icons.search_rounded),
                suffixIcon: _searchController.text.isEmpty
                    ? null
                    : IconButton(
                        icon: const Icon(Icons.clear_rounded),
                        onPressed: () {
                          _searchController.clear();
                          context.read<PrestasiProvider>().setSearch('');
                        },
                      ),
              ),
            ),
          ),
          Expanded(child: _buildList(provider)),
        ],
      ),
      floatingActionButton: FloatingActionButton(
        onPressed: () => _openForm(),
        backgroundColor: AppColors.primary,
        foregroundColor: Colors.white,
        child: const Icon(Icons.add_rounded),
      ),
    );
  }

  Widget _buildList(PrestasiProvider provider) {
    if (provider.loading) return const LoadingView();
    if (provider.error != null && provider.items.isEmpty) {
      return ErrorView(message: provider.error!, onRetry: provider.load);
    }
    if (provider.items.isEmpty) {
      return const EmptyView(title: 'Tidak ada data prestasi');
    }

    return RefreshIndicator(
      onRefresh: () => provider.load(showLoader: false),
      child: ListView.builder(
        controller: _scrollController,
        padding: const EdgeInsets.fromLTRB(16, 0, 16, 96),
        itemCount: provider.items.length + (provider.hasMore ? 1 : 0),
        itemBuilder: (context, i) {
          if (i >= provider.items.length) {
            return const Padding(
              padding: EdgeInsets.all(16),
              child: Center(
                child: CircularProgressIndicator(color: AppColors.primary),
              ),
            );
          }
          return _PrestasiCard(
            prestasi: provider.items[i],
            onTap: () => _openDetail(provider.items[i]),
          );
        },
      ),
    );
  }

  void _openDetail(Prestasi p) {
    Navigator.of(context).push(
      MaterialPageRoute(builder: (_) => PrestasiDetailScreen(prestasi: p)),
    );
  }

  void _openForm() {
    Navigator.of(context).push(
      MaterialPageRoute(builder: (_) => const PrestasiFormScreen()),
    );
  }
}

class _PrestasiCard extends StatelessWidget {
  const _PrestasiCard({required this.prestasi, required this.onTap});

  final Prestasi prestasi;
  final VoidCallback onTap;

  @override
  Widget build(BuildContext context) {
    final color = _statusColor(prestasi.statusVerifikasi);

    return Card(
      margin: const EdgeInsets.only(bottom: 10),
      child: ListTile(
        onTap: onTap,
        contentPadding: const EdgeInsets.symmetric(horizontal: 14, vertical: 6),
        leading: Container(
          width: 44,
          height: 44,
          decoration: BoxDecoration(
            gradient: AppColors.primaryGradient,
            shape: BoxShape.circle,
          ),
          child: const Icon(
            Icons.emoji_events_rounded,
            color: Colors.white,
            size: 22,
          ),
        ),
        title: Text(
          prestasi.namaLomba ?? '-',
          style: const TextStyle(fontWeight: FontWeight.w600),
          maxLines: 1,
          overflow: TextOverflow.ellipsis,
        ),
        subtitle: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              prestasi.mahasiswa?.nama ?? '-',
              style: TextStyle(color: Colors.grey.shade600, fontSize: 12.5),
            ),
            const SizedBox(height: 4),
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
              decoration: BoxDecoration(
                color: color.withValues(alpha: 0.12),
                borderRadius: BorderRadius.circular(20),
              ),
              child: Text(
                prestasi.statusVerifikasi ?? '-',
                style: TextStyle(
                  color: color,
                  fontSize: 10.5,
                  fontWeight: FontWeight.w700,
                ),
              ),
            ),
          ],
        ),
        trailing: const Icon(Icons.chevron_right_rounded, color: Colors.grey),
      ),
    );
  }

  Color _statusColor(String? status) {
    switch (status) {
      case 'Disetujui':
        return Colors.green.shade600;
      case 'Ditolak':
        return Colors.red.shade400;
      default:
        return Colors.orange.shade700;
    }
  }
}
