import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../../core/theme/app_theme.dart';
import '../../core/widgets/gradient_background.dart';
import '../../core/widgets/status_views.dart';
import '../../data/models/beasiswa.dart';
import '../../data/providers/auth_provider.dart';
import '../../data/providers/beasiswa_provider.dart';
import 'beasiswa_form_screen.dart';
import 'program_beasiswa_screen.dart';

class BeasiswaListScreen extends StatefulWidget {
  const BeasiswaListScreen({super.key});

  @override
  State<BeasiswaListScreen> createState() => _BeasiswaListScreenState();
}

class _BeasiswaListScreenState extends State<BeasiswaListScreen> {
  final _searchController = TextEditingController();
  final _scrollController = ScrollController();

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      if (mounted) context.read<BeasiswaProvider>().load();
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
      context.read<BeasiswaProvider>().loadMore();
    }
  }

  @override
  Widget build(BuildContext context) {
    final provider = context.watch<BeasiswaProvider>();
    final isAdmin = context.watch<AuthProvider>().user?.isAdmin ?? false;
    final isMahasiswa =
        context.watch<AuthProvider>().user?.isMahasiswa ?? false;

    return Scaffold(
      appBar: GradientAppBar(
        title: isMahasiswa ? 'Beasiswa Saya' : 'Beasiswa',
        actions: [
          if (isAdmin)
            IconButton(
              tooltip: 'Hitung Rekomendasi',
              icon: const Icon(Icons.auto_awesome_rounded),
              onPressed: () => _hitungRekomendasi(context),
            ),
          IconButton(
            tooltip: 'Program Beasiswa',
            icon: const Icon(Icons.workspace_premium_rounded),
            onPressed: () => _openPrograms(),
          ),
        ],
      ),
      body: Column(
        children: [
          Padding(
            padding: const EdgeInsets.all(16),
            child: TextField(
              controller: _searchController,
              onChanged: (v) => context.read<BeasiswaProvider>().setSearch(v),
              decoration: InputDecoration(
                hintText: 'Cari program / mahasiswa / NIM...',
                prefixIcon: const Icon(Icons.search_rounded),
                suffixIcon: _searchController.text.isEmpty
                    ? null
                    : IconButton(
                        icon: const Icon(Icons.clear_rounded),
                        onPressed: () {
                          _searchController.clear();
                          context.read<BeasiswaProvider>().setSearch('');
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

  Widget _buildList(BeasiswaProvider provider) {
    if (provider.loading) return const LoadingView();
    if (provider.error != null && provider.items.isEmpty) {
      return ErrorView(message: provider.error!, onRetry: provider.load);
    }
    if (provider.items.isEmpty) {
      return const EmptyView(title: 'Tidak ada data beasiswa');
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
          return _BeasiswaCard(
            beasiswa: provider.items[i],
            onDelete: () => _confirmDelete(provider.items[i]),
          );
        },
      ),
    );
  }

  void _openForm() {
    Navigator.of(context).push(
      MaterialPageRoute(builder: (_) => const BeasiswaFormScreen()),
    );
  }

  void _openPrograms() {
    Navigator.of(context).push(
      MaterialPageRoute(builder: (_) => const ProgramBeasiswaScreen()),
    );
  }

  Future<void> _hitungRekomendasi(BuildContext context) async {
    final provider = context.read<BeasiswaProvider>();
    try {
      final message = await provider.hitungRekomendasi();
      if (context.mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text(message)),
        );
      }
    } catch (e) {
      if (context.mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('$e')),
        );
      }
    }
  }

  Future<void> _confirmDelete(Beasiswa b) async {
    final confirm = await showDialog<bool>(
      context: context,
      builder: (_) => AlertDialog(
        title: const Text('Hapus Beasiswa'),
        content: Text(
          'Hapus pengajuan ${b.programBeasiswa?.namaBeasiswa ?? ''}?',
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context, false),
            child: const Text('Batal'),
          ),
          FilledButton(
            style: FilledButton.styleFrom(backgroundColor: Colors.red.shade400),
            onPressed: () => Navigator.pop(context, true),
            child: const Text('Hapus'),
          ),
        ],
      ),
    );

    if (confirm == true && context.mounted) {
      try {
        await context.read<BeasiswaProvider>().delete(b.id);
        if (context.mounted) {
          ScaffoldMessenger.of(context).showSnackBar(
            const SnackBar(content: Text('Beasiswa berhasil dihapus')),
          );
        }
      } catch (e) {
        if (context.mounted) {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(content: Text('$e')),
          );
        }
      }
    }
  }
}

class _BeasiswaCard extends StatelessWidget {
  const _BeasiswaCard({required this.beasiswa, required this.onDelete});

  final Beasiswa beasiswa;
  final VoidCallback onDelete;

  @override
  Widget build(BuildContext context) {
    final statusColor = _statusColor(beasiswa.status);
    final rekomendasi = beasiswa.fuzzyHasil?.hasilRekomendasi;

    return Card(
      margin: const EdgeInsets.only(bottom: 10),
      child: Padding(
        padding: const EdgeInsets.all(14),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              children: [
                Container(
                  padding: const EdgeInsets.symmetric(
                    horizontal: 10,
                    vertical: 5,
                  ),
                  decoration: BoxDecoration(
                    color: statusColor.withValues(alpha: 0.12),
                    borderRadius: BorderRadius.circular(20),
                  ),
                  child: Text(
                    beasiswa.status ?? '-',
                    style: TextStyle(
                      color: statusColor,
                      fontSize: 11.5,
                      fontWeight: FontWeight.w700,
                    ),
                  ),
                ),
                if (rekomendasi != null) ...[
                  const SizedBox(width: 8),
                  Container(
                    padding: const EdgeInsets.symmetric(
                      horizontal: 10,
                      vertical: 5,
                    ),
                    decoration: BoxDecoration(
                      color: AppColors.secondary.withValues(alpha: 0.12),
                      borderRadius: BorderRadius.circular(20),
                    ),
                    child: Text(
                      rekomendasi,
                      style: const TextStyle(
                        color: AppColors.secondary,
                        fontSize: 11.5,
                        fontWeight: FontWeight.w700,
                      ),
                    ),
                  ),
                ],
                const Spacer(),
                IconButton(
                  onPressed: onDelete,
                  icon: const Icon(Icons.delete_outline_rounded),
                  color: Colors.red.shade300,
                  visualDensity: VisualDensity.compact,
                ),
              ],
            ),
            const SizedBox(height: 8),
            Text(
              beasiswa.programBeasiswa?.namaBeasiswa ?? '-',
              style: const TextStyle(fontSize: 15, fontWeight: FontWeight.w700),
            ),
            const SizedBox(height: 2),
            Text(
              beasiswa.programBeasiswa?.penyelenggara ?? '-',
              style: TextStyle(color: Colors.grey.shade600, fontSize: 12.5),
            ),
            const SizedBox(height: 8),
            Row(
              children: [
                Icon(Icons.person_outline_rounded,
                    color: Colors.grey.shade500, size: 18),
                const SizedBox(width: 6),
                Expanded(
                  child: Text(
                    '${beasiswa.mahasiswa?.nama ?? '-'} (${beasiswa.mahasiswa?.nim ?? '-'})',
                    style: TextStyle(
                      color: Colors.grey.shade700,
                      fontSize: 13,
                    ),
                    maxLines: 1,
                    overflow: TextOverflow.ellipsis,
                  ),
                ),
              ],
            ),
            const SizedBox(height: 6),
            Row(
              children: [
                Icon(Icons.event_rounded, color: Colors.grey.shade500, size: 18),
                const SizedBox(width: 6),
                Text(
                  'Pengajuan: ${_shortDate(beasiswa.tanggalPengajuan)}',
                  style: TextStyle(
                    color: Colors.grey.shade700,
                    fontSize: 13,
                  ),
                ),
              ],
            ),
            if (beasiswa.fuzzyHasil?.nilaiFuzzy != null) ...[
              const SizedBox(height: 6),
              Text(
                'Skor Fuzzy: ${beasiswa.fuzzyHasil!.nilaiFuzzy}',
                style: const TextStyle(
                  color: AppColors.primary,
                  fontSize: 13,
                  fontWeight: FontWeight.w700,
                ),
              ),
            ],
          ],
        ),
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

  String _shortDate(String? date) {
    if (date == null || date.isEmpty) return '-';
    final d = DateTime.tryParse(date);
    if (d == null) return date;
    return '${d.day}/${d.month}/${d.year}';
  }
}
