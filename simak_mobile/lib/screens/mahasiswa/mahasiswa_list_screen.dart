import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../../core/theme/app_theme.dart';
import '../../core/widgets/gradient_background.dart';
import '../../core/widgets/status_views.dart';
import '../../data/models/mahasiswa.dart';
import '../../data/providers/mahasiswa_provider.dart';
import 'mahasiswa_detail_screen.dart';
import 'mahasiswa_form_screen.dart';

class MahasiswaListScreen extends StatefulWidget {
  const MahasiswaListScreen({super.key});

  @override
  State<MahasiswaListScreen> createState() => _MahasiswaListScreenState();
}

class _MahasiswaListScreenState extends State<MahasiswaListScreen> {
  final _searchController = TextEditingController();
  final _scrollController = ScrollController();

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      if (mounted) context.read<MahasiswaProvider>().load();
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
      context.read<MahasiswaProvider>().loadMore();
    }
  }

  @override
  Widget build(BuildContext context) {
    final provider = context.watch<MahasiswaProvider>();

    return Scaffold(
      appBar: GradientAppBar(title: 'Mahasiswa'),
      body: Column(
        children: [
          Padding(
            padding: const EdgeInsets.all(16),
            child: TextField(
              controller: _searchController,
              onChanged: (v) =>
                  context.read<MahasiswaProvider>().setSearch(v),
              decoration: InputDecoration(
                hintText: 'Cari nama / NIM / prodi...',
                prefixIcon: const Icon(Icons.search_rounded),
                suffixIcon: _searchController.text.isEmpty
                    ? null
                    : IconButton(
                        icon: const Icon(Icons.clear_rounded),
                        onPressed: () {
                          _searchController.clear();
                          context.read<MahasiswaProvider>().setSearch('');
                        },
                      ),
              ),
            ),
          ),
          Expanded(
            child: _buildList(provider),
          ),
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

  Widget _buildList(MahasiswaProvider provider) {
    if (provider.loading) return const LoadingView();
    if (provider.error != null && provider.items.isEmpty) {
      return ErrorView(message: provider.error!, onRetry: provider.load);
    }
    if (provider.items.isEmpty) {
      return const EmptyView(title: 'Tidak ada data mahasiswa');
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
          return _MahasiswaCard(
            mahasiswa: provider.items[i],
            onTap: () => _openDetail(provider.items[i]),
          );
        },
      ),
    );
  }

  void _openDetail(Mahasiswa m) {
    Navigator.of(context).push(
      MaterialPageRoute(builder: (_) => MahasiswaDetailScreen(mahasiswa: m)),
    );
  }

  void _openForm() {
    Navigator.of(context).push(
      MaterialPageRoute(builder: (_) => const MahasiswaFormScreen()),
    );
  }
}

class _MahasiswaCard extends StatelessWidget {
  const _MahasiswaCard({required this.mahasiswa, required this.onTap});

  final Mahasiswa mahasiswa;
  final VoidCallback onTap;

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: const EdgeInsets.only(bottom: 10),
      child: ListTile(
        onTap: onTap,
        contentPadding: const EdgeInsets.symmetric(horizontal: 14, vertical: 6),
        leading: CircleAvatar(
          backgroundColor: AppColors.primary.withValues(alpha: 0.12),
          child: Text(
            mahasiswa.nama.isNotEmpty ? mahasiswa.nama[0].toUpperCase() : '?',
            style: const TextStyle(
              color: AppColors.primary,
              fontWeight: FontWeight.w700,
            ),
          ),
        ),
        title: Text(
          mahasiswa.nama,
          style: const TextStyle(fontWeight: FontWeight.w600),
          maxLines: 1,
          overflow: TextOverflow.ellipsis,
        ),
        subtitle: Text(
          '${mahasiswa.nim} • ${mahasiswa.prodi ?? '-'}',
          style: TextStyle(color: Colors.grey.shade600),
        ),
        trailing: Row(
          mainAxisSize: MainAxisSize.min,
          children: [
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 5),
              decoration: BoxDecoration(
                color: _statusColor(mahasiswa.status).withValues(alpha: 0.12),
                borderRadius: BorderRadius.circular(20),
              ),
              child: Text(
                mahasiswa.status ?? '-',
                style: TextStyle(
                  color: _statusColor(mahasiswa.status),
                  fontSize: 11,
                  fontWeight: FontWeight.w700,
                ),
              ),
            ),
            const Icon(Icons.chevron_right_rounded, color: Colors.grey),
          ],
        ),
      ),
    );
  }

  Color _statusColor(String? status) {
    switch (status) {
      case 'Aktif':
        return Colors.green.shade600;
      case 'Cuti':
        return Colors.orange.shade700;
      case 'Lulus':
        return Colors.blue.shade700;
      default:
        return Colors.red.shade400;
    }
  }
}
